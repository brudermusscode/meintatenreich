<?php

// create session when cookies are accepted and no
// session was started before
if (!isset($_SESSION) && isset($_COOKIE['cookies']) && $_COOKIE['cookies'] == 'true') {
    session_start();
}

// include prepare file
require_once "_.prepare.php";

// get system settings
$get_system_settings = $pdo->prepare("SELECT * FROM system_settings, system_urls WHERE system_urls.id = ?");
$get_system_settings->execute([$conf["environment"]]);
$system_settings = $get_system_settings->fetch();

// create main array with system specific information
$main = [
    "name" => $system_settings->name,
    "year" => $system_settings->year,
    "maintenance" => $system_settings->maintenance,
    "displayerrors" => $system_settings->display_errors,
    "mwstr" => $system_settings->mwstr,
    "fulldate" => date("Y-m-d H:i:s"),
    "endlessCookie" => time() + (10 * 365 * 24 * 60 * 60)
];

// create url array with system urls
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

// create mail array for predefined mail constructions
$mail = [
    "header"  => 'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=utf-8' . "\r\n" .
        'From: MeinTatenReich <noreply@meintatenreich.de>' . "\r\n",
    "subjectSignup" => 'Deine Registrierung auf MeinTatenreich - Jetzt abschließen!',
    "subjectForgotPassword" => 'Passwort vergessen?',
    "subjectOrder" => 'Bestätigung Deiner Bestellung auf MeinTatenreich'
];

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

    // check login state for user
    public function isAuthed($pdo)
    {

        if (isset($_COOKIE['TOK']) && isset($_COOKIE['SER']) && !empty($_SESSION)) {

            $cookieToken = $_COOKIE['TOK'];
            $cookieSerial = $_COOKIE['SER'];
            $sessionToken = $_SESSION["token"];
            $sessionSerial = $_SESSION["serial"];
            $sessionId = $_SESSION["id"];

            // check if cookies and serial are same
            if (
                $cookieToken == $sessionToken &&
                $cookieSerial == $sessionSerial
            ) {

                // get session from database
                $getSession = $pdo->prepare("SELECT * FROM system_sessions WHERE uid = ? AND token = ? AND serial = ?");
                $getSession->execute([$sessionId, $sessionToken, $sessionSerial]);

                if ($getSession->rowCount() > 0) {

                    // everything's fine, user is logged in
                    return true;
                } else {
                    $this->logout();
                }
            } else {
                $this->logout();
            }
        } else {
            $this->logout();
        }
    }

    // create session cookie
    public static function createCookie($token, $serial)
    {

        setcookie('TOK', $token, time() + (86400) * 30, "/");
        setcookie('SER', $serial, time() + (86400) * 30, "/");
    }

    // create session session
    public static function createSession($array, $token, $serial, $amount)
    {

        foreach ($array as $k => $v) {
            $_SESSION[$k] = $v;
        }

        $_SESSION["token"] = $token;
        $_SESSION["serial"] = $serial;
        $_SESSION["shoppingCardAmount"] = $amount;

        return $_SESSION;
    }

    // create unique strings
    public static function createString($len)
    {
        $s = bin2hex(random_bytes($len));
        return $s;
    }

    // logout from session
    public static function logout()
    {
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

    public static function validatePassword($str)
    {
        if (!preg_match('/[^a-z0-9=.,_+*#~?!&%$§\-]/i', $str)) {
            return true;
        }

        return false;
    }

    public static function removeFileType($str)
    {
        $str = preg_replace("/(.+)\.php$/", "$1", $str);
        return $str;
    }

    public static function tryExecute($stmt, $params, $connection, $commit = false)
    {

        // TO DO: add support for several queries
        // -----
        // check if passed $params is of array type
        if (!is_array($params)) {
            $params = [$params];
        }

        try {

            // try executing the statement
            $stmt->execute($params);

            // store error information
            $return = [
                "status" => true,
                "lastInsertId" => $connection->lastInsertId()
            ];

            // objectify error information array
            $return = (object) $return;

            // commit changes if true
            if ($commit) {
                $connection->commit();
            }

            // return the object back to the script
            return $return;
        } catch (PDOException $e) {

            // catch error information
            $return = [
                "status" => false,
                "message" => $e->getMessage(),
                "code" => $e->getCode()
            ];

            // rollback data and return error information
            if ($commit) {
                $connection->rollback();
            }

            return $return;
        }

        return false;
    }

    public static function trySendMail($address, $subject, $body, $header)
    {
        try {

            mail($address, $subject, $body, $header);
            return true;
        } catch (PDOException $e) {

            $errorInformation = [
                "status" => false,
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ];

            return $errorInformation;
        }

        return false;
    }
}


class administrate
{

    function getDump($dump)
    {

        switch ($dump) {
            case "session":
                return print_r($_SESSION);
                break;
            case "request":
                return print_r($_REQUEST);
                break;
            default:
                return false;
                break;
        }
    }
}

// get information about logged in customer
$loggedIn = $login->isAuthed($pdo);
if ($loggedIn) {

    $sessionid = $_SESSION['id'];

    // convert SESSION array to object and store in $my
    $my = (object) $_SESSION;

    // check customers billing preference
    $my->billingPreference = false;
    $billingPreference = $pdo->prepare("
        SELECT *, customer_billings.id AS bid, customer_billings_prefs.id AS bpid 
        FROM customer_billings_prefs, customer_billings  
        WHERE customer_billings.id = customer_billings_prefs.pid 
        AND customer_billings_prefs.uid = ? 
    ");
    $billingPreference->execute([$my->id]);

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
    $addressPreference->execute([$my->id]);

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
