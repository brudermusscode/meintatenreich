<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// create debug array
$debugArray = [];

if (
    isset($_REQUEST["mail"], $_REQUEST["password"], $_REQUEST["password2"], $_REQUEST["g-recaptcha-response"], $_REQUEST["agb"])
    && !$loggedIn
) {

    // variablize
    $agb = htmlspecialchars($_REQUEST["agb"]);
    $inputmail = htmlspecialchars($_REQUEST["mail"]);
    $password = $_REQUEST["password"];
    $password2 = $_REQUEST["password2"];
    $remoteaddr = $_SERVER['REMOTE_ADDR'];
    $captcha = $_REQUEST["g-recaptcha-response"];

    // add debug
    $debugArray["requestComplete"] = true;

    // check if cookies are accepted
    if (isset($_COOKIE['cookies']) && $_COOKIE['cookies'] === 'true') {

        // add debug
        $debugArray["cookiesAccepted"] = true;

        // check if disclaimer box is checked
        if ($agb === 'on') {

            // add debug
            $debugArray["disclaimerAccepted"] = true;

            // check password matching
            if ($password === $password2) {

                // add debug
                $debugArray["passwordsMatch"] = true;

                // check for password range
                if (strlen($password) >= 8 && strlen($password) <= 32) {

                    // add debug
                    $debugArray["passwordValid"] = true;

                    // check for grecaptcha
                    $captchaResponse = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $conf["recaptcha_privatekey"] . "&response=" . $captcha . "&remoteip=" . $remoteaddr));
                    if ($captchaResponse->success) {

                        // add debug
                        $debugArray["gRecaptchaValid"] = true;

                        // check email format
                        if (filter_var($inputmail, FILTER_VALIDATE_EMAIL)) {

                            // start mysql transactions
                            $pdo->beginTransaction();

                            // add debug
                            $debugArray["emailValid"] = true;

                            // variablize
                            $httpxfor = $login->get_client_ip();
                            $key = $login->createString(64);
                            $displayname = 'customer-' . $login->createString(12);

                            // hash password
                            $password = password_hash($password, PASSWORD_DEFAULT);

                            // insert new customer
                            $insertCustomer = $pdo->prepare("
                                INSERT INTO customer (displayname, mail, password, remoteaddr, httpx) 
                                VALUES (?,?,?,?,?)
                            ");
                            $try = $shop->tryExecute($insertCustomer, [$displayname, $inputmail, $password, $remoteaddr, $httpxfor], $pdo);

                            if (is_array($try) && $try["status"] == true) {

                                // add debug
                                $debugArray["insertCustomer"] = true;

                                // get last inserted id
                                $newid = $try["lastInsertId"];

                                // create login
                                $token = $login->createString(64);
                                $serial = $login->createString(64);

                                // create session
                                $insertSession = $pdo->prepare("INSERT INTO system_sessions (uid,token,serial,remoteaddr,httpx) VALUES (?,?,?,?,?)");
                                $try = $shop->tryExecute($insertSession, [$newid, $token, $serial, $remoteaddr, $httpxfor], $pdo);

                                if (is_array($try) && $try["status"] == true) {

                                    // add debug
                                    $debugArray["insertSession"] = true;

                                    // insert admin log
                                    $insertAdminLog = $pdo->prepare("INSERT INTO admin_overview (rid, ttype) VALUES (?,'customer')");
                                    $try = $shop->tryExecute($insertAdminLog, [$newid], $pdo);

                                    if (is_array($try) && $try["status"] == true) {

                                        // add debug
                                        $debugArray["insertAdminLog"] = true;

                                        // create verification key
                                        $insertVerification = $pdo->prepare("INSERT INTO customer_verifications (uid, vkey) VALUES (?,?)");
                                        $try = $shop->tryExecute($insertVerification, [$newid, $key], $pdo);

                                        if (is_array($try) && $try["status"] == true) {

                                            // add debug
                                            $debugArray["insertVerification"] = true;

                                            // prepare mail's body
                                            $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/signup.html');
                                            $mailbody = str_replace('%mail%', $inputmail, $mailbody);
                                            $mailbody = str_replace('%url%', $url["main"] . "/my/verification?id=" . $newid . "&key=" . $key, $mailbody);

                                            // send mail
                                            $try = $shop->trySendMail(
                                                $inputmail,
                                                "Deine Registrierung auf MeinTatenreich - Jetzt abschließen!",
                                                $mailbody,
                                                $mail["header"]
                                            );

                                            if ($try) {

                                                // add debug
                                                $debugArray["success"] = true;

                                                $login->createCookie($newid, $displayname, $token, $serial);
                                                $login->createSession($newid, $displayname, $token, $serial);

                                                $pdo->commit();
                                                exit(json_encode($debugArray));
                                            } else {
                                                exit("0");
                                            }
                                        } else {
                                            exit("0");
                                        }
                                    } else {
                                        exit("0");
                                    }
                                } else {
                                    exit("0");
                                }
                            } else {

                                // switch through error cases and give back
                                // message to user
                                switch ($try["code"]) {
                                    case "23000": // duplicate key
                                        exit("7");
                                        break;
                                    default:
                                        exit("0");
                                }
                            }
                        } else {
                            exit('6');
                        } // email has wrong format

                    } else {
                        exit('5');
                    } // grecaptcha wrong

                } else {
                    exit('4');
                } // passwords out of range

            } else {
                exit('3');
            } // passwords not matching

        } else {
            exit('2');
        } // agb not accepted

    } else {
        exit('1');
    } // cookies not accepted

} else {
    exit('0');
}
