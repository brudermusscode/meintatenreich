<?php

$login = new Login($pdo, $my);

class Login
{

    public object $pdo;

    public function __construct(object $pdo, object $my)
    {
        $this->pdo = $pdo;
        $this->my = $my;
    }

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
    public function isAuthed()
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
                $getSession = $this->pdo->prepare("SELECT * FROM system_sessions WHERE uid = ? AND token = ? AND serial = ?");
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
            return false;
        }
    }

    // create session cookie
    public static function createCookie($token, $serial)
    {

        setcookie('TOK', $token, time() + (86400) * 30, "/");
        setcookie('SER', $serial, time() + (86400) * 30, "/");
    }

    // create session session
    public static function createSession($array, $token, $serial, $amount, $check = true)
    {

        foreach ($array as $k => $v) {
            $_SESSION[$k] = $v;
        }

        if ($check) {
            $_SESSION["token"] = $token;
            $_SESSION["serial"] = $serial;
            $_SESSION["shoppingCardAmount"] = $amount;
        }

        return $_SESSION;
    }

    public function resetSession()
    {
        if ($this->isAuthed()) {

            // get user data and compare
            $getUserData = $this->pdo->prepare("SELECT * FROM customer WHERE id = ?");
            if ($getUserData->execute([$this->my->id])) {

                $u = $getUserData->fetch();
                $this->createSession($u, NULL, NULL, NULL, false);
            }
        }
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

    public static function encryptPassword(string $password)
    {
        $md5Password = md5($password);
        $hashedMD5password = password_hash($md5Password, PASSWORD_ARGON2ID);

        return $hashedMD5password;
    }

    public static function verifyPassword(string $password, $hash)
    {
        $md5Password = md5($password);
        return password_verify($md5Password, $hash);
    }
}
