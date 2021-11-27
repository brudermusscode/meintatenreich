<?php

if (!isset($_SESSION) && isset($_COOKIE['cookies']) && $_COOKIE['cookies'] == 'true') {
    session_start();
}

require_once "_.prepare.php";

// get system settings
$get_system_settings = $pdo->prepare("SELECT * FROM system_settings, system_urls WHERE system_urls.id = ?");
$get_system_settings->execute([$conf["environment"]]);
$system_settings = $get_system_settings->fetch();

$main = [
    "name" => $system_settings->name,
    "year" => $system_settings->year,
    "maintenance" => $system_settings->maintenance,
    "displayerrors" => $system_settings->display_errors,
    "mwstr" => $system_settings->mwstr,
    "fulldate" => date("Y-m-d H:i:s")
];

$url = [
    "main" => $system_settings->main,
    "maintenance" => $system_settings->maintenance,
    "intern" => $system_settings->intern,
    "dashbrd" => $system_settings->dashboard,
    "error" => $system_settings->error,
    "css" => $system_settings->css,
    "js" => $system_settings->js,
    "img" => $system_settings->img,
    "icons" => $system_settings->icons,
    "upload" => $system_settings->upload,
    "mobile" => $system_settings->mobile
];


// LOGIN CLASS
$login = new login;

class login
{
    public static function get_client_ip()
    {
        if (!isset($_SERVER['REMOTE_ADDR'])) {
            return NULL;
        }

        $proxy_header = "HTTP_X_FORWARDED_FOR";
        $trusted_proxies = ["2001:db8::1", "192.168.50.1"];

        if (in_array($_SERVER['REMOTE_ADDR'], $trusted_proxies)) {

            if (array_key_exists($proxy_header, $_SERVER)) {

                $proxy_list = explode(",", $_SERVER[$proxy_header]);
                $client_ip = trim(end($proxy_list));

                if (filter_var($client_ip, FILTER_VALIDATE_IP)) {
                    return $client_ip;
                } else {
                    // Validation failed - beat the guy who configured the proxy or
                    // the guy who created the trusted proxy list?
                    // TODO: some error handling to notify about the need of punishment
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    // CHECK LOGIN STATE
    public function isAuthed($pdo)
    {

        if (isset($_COOKIE['UID']) && isset($_COOKIE['TOK']) && isset($_COOKIE['SER']) && empty($_SESSION)) {

            $this->logout();
        } else if (isset($_COOKIE['UID']) && isset($_COOKIE['TOK']) && isset($_COOKIE['SER']) && !empty($_SESSION)) {

            $myid = $_COOKIE['UID'];
            $mytoken = $_COOKIE['TOK'];
            $myserial = $_COOKIE['SER'];

            $get_session_information = $pdo->prepare("SELECT * FROM system_sessions WHERE uid = ? AND token = ? AND serial = ?");
            $get_session_information->execute([$myid, $mytoken, $myserial]);

            // check session existence
            if ($get_session_information->rowCount() > 0) {

                $sess = $get_session_information->fetch();

                // CHECK IF COOKIES HAVE LEGIT VALUES
                if ($sess->uid == $myid && $sess->token == $mytoken && $sess->serial == $myserial) {

                    // CHECK IF ACTUAL SESSION HAS LEGIT VALUES
                    if ($sess->uid == $_SESSION['id'] && $sess->token == $_SESSION['token'] && $sess->serial == $_SESSION['serial']) {

                        // RETURN TRUE:: USER IS AUTHED!
                        return true;
                    }
                }
            } else {

                // delete all cookies
                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);

                    $i = 0;
                    $len = count($cookies);

                    foreach ($cookies as $cookie) {

                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        setcookie($name, '', time() - 1000);
                        setcookie($name, '', time() - 1000, '/');

                        $i++;

                        if ($i == $len - 1) {
                            header("Refresh:0");
                        }
                    }
                }
            }
        }
    }

    // CREATE COOKIE
    public static function createCookie($id, $username, $token, $serial)
    {
        setcookie('UID', $id, time() + (86400) * 30, "/");
        setcookie('UN', $username, time() + (86400) * 30, "/");
        setcookie('TOK', $token, time() + (86400) * 30, "/");
        setcookie('SER', $serial, time() + (86400) * 30, "/");
    }

    // CREATE SESSION
    public static function createSession($id, $username, $token, $serial)
    {
        $_SESSION['id'] = $id;
        $_SESSION['displayname'] = $username;
        $_SESSION['token'] = $token;
        $_SESSION['serial'] = $serial;
    }

    // CREATE UNIQUE STRING
    public static function createString($len)
    {
        $s = bin2hex(random_bytes($len));
        return $s;
    }

    // LOGOUT
    public static function logout()
    {
        setcookie('UN', '', time() - 1, "/");
        setcookie('TOK', '', time() - 1, "/");
        setcookie('SER', '', time() - 1, "/");
    }
}

$shop = new shop;

class shop
{
    public static function validateName($nameString)
    {
        if (!preg_match('/[^a-z\-\s]/i', $nameString)) {
            return true;
        }

        return false;
    }
}


// get information about logged in customer
$loggedIn = $login->isAuthed($pdo);
if ($loggedIn) {

    $sessionid = $_SESSION['id'];

    $getUserData = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
    $getUserData->execute([$sessionid]);
    $my = $getUserData->fetch();

    $getScardAmt = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ? AND active = '1'");
    $getScardAmt->execute([$sessionid]);
    $scardamt = $getScardAmt->rowCount();

    // check customers billing preference
    $my->billingPreference = false;
    $billingPreference = $pdo->prepare("SELECT * FROM customer_billings_prefs WHERE uid = ?");
    $billingPreference->execute([$sessionid]);

    if ($billingPreference->rowCount() > 0) {

        $my->billingPreference = true;
        $bp = $billingPreference->fetch();
    }

    // check customers address preference
    $my->addressPreference = false;
    $addressPreference = $pdo->prepare("
        SELECT *, customer_addresses.id AS aid, customer_addresses_prefs.id AS apid 
        FROM customer_addresses_prefs, customer_addresses  
        WHERE customer_addresses.id = customer_addresses_prefs.adid 
        AND customer_addresses_prefs.uid = ? 
    ");
    $addressPreference->execute([$sessionid]);

    if ($addressPreference->rowCount() > 0) {

        $my->addressPreference = true;
        $ap = $addressPreference->fetch();
    }


    // check authentification for admin cookie
    $isauthedAdmin = false;
    if (isset($_COOKIE['EzGqsVq6rY8xE5'])) {
        if ($_COOKIE['EzGqsVq6rY8xE5'] === 'CjkzqEy2uhSsqc') {
            $isauthedAdmin = true;
        }
    }
}


// check for maintenance
if ($main["maintenance"] == '1') {
    if ($login->isAuthed($pdo)) {
        if ($user['admin'] !== '1') {
            header('location: ./maintenance');
        }
    } else if ($isauthedAdmin === false) {
        header('location: ./maintenance');
    }
}

include_once "_.functions.php";
