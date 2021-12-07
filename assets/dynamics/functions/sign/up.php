<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!"
];

// objectify response array
$return = (object) $return;

if (
    isset(
        $_REQUEST["mail"],
        $_REQUEST["password"],
        $_REQUEST["password2"],
        $_REQUEST["g-recaptcha-response"],
        $_REQUEST["agb"]
    )
    && !$loggedIn
) {

    // variablize
    $agb = htmlspecialchars($_REQUEST["agb"]);
    $inputmail = htmlspecialchars($_REQUEST["mail"]);
    $password = $_REQUEST["password"];
    $password2 = $_REQUEST["password2"];
    $remoteaddr = $_SERVER['REMOTE_ADDR'];
    $captcha = $_REQUEST["g-recaptcha-response"];

    // check if cookies are accepted
    if (isset($_COOKIE['cookies']) && $_COOKIE['cookies'] === 'true') {

        // check if disclaimer box is checked
        if ($agb === 'on') {

            // check password matching
            if ($password === $password2) {

                // check for password range
                if (strlen($password) >= 8 && strlen($password) <= 32) {

                    // check for grecaptcha
                    $captchaResponse = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $conf["recaptcha_privatekey"] . "&response=" . $captcha . "&remoteip=" . $remoteaddr));
                    if ($captchaResponse->success) {

                        // check email format
                        if (filter_var($inputmail, FILTER_VALIDATE_EMAIL)) {

                            // start mysql transactions
                            $pdo->beginTransaction();

                            // variablize
                            $httpxfor = $login->get_client_ip();
                            $key = $login->createString(64);
                            $displayname = 'customer-' . $login->createString(12);

                            // hash password
                            $password = password_hash($password, PASSWORD_DEFAULT);

                            // insert new customer
                            $insertCustomer = $pdo->prepare("INSERT INTO customer (displayname, mail, password, remoteaddr, httpx) VALUES (?,?,?,?,?)");
                            $insertCustomer = $shop->tryExecute($insertCustomer, [$displayname, $inputmail, $password, $remoteaddr, $httpxfor], $pdo, false);

                            if ($insertCustomer->status) {

                                // get last inserted id
                                $newid = $insertCustomer->lastInsertId;

                                // create login
                                $token = $login->createString(64);
                                $serial = $login->createString(64);

                                // create session
                                $insertSession = $pdo->prepare("INSERT INTO system_sessions (uid,token,serial,remoteaddr,httpx) VALUES (?,?,?,?,?)");
                                $insertSession = $shop->tryExecute($insertSession, [$newid, $token, $serial, $remoteaddr, $httpxfor], $pdo, false);

                                if ($insertSession->status) {

                                    // insert admin log
                                    $insertAdminLog = $pdo->prepare("INSERT INTO admin_overview (rid, ttype) VALUES (?,'customer')");
                                    $insertAdminLog = $shop->tryExecute($insertAdminLog, [$newid], $pdo, false);

                                    if ($insertAdminLog->status) {

                                        // create verification key
                                        $insertVerification = $pdo->prepare("INSERT INTO customer_verifications (uid, vkey) VALUES (?,?)");
                                        $insertVerification = $shop->tryExecute($insertVerification, [$newid, $key], $pdo, true);

                                        if ($insertVerification->status) {

                                            // prepare mail's body
                                            $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/signup.html');
                                            $mailbody = str_replace('%mail%', $inputmail, $mailbody);
                                            $mailbody = str_replace('%url%', $url["main"] . "/my/verification?id=" . $newid . "&key=" . $key, $mailbody);

                                            // send mail
                                            $sendMail = $shop->trySendMail(
                                                $inputmail,
                                                "Deine Registrierung auf MeinTatenreich - Jetzt abschließen!",
                                                $mailbody,
                                                $mail["header"]
                                            );

                                            if ($sendMail) {

                                                $getUserData = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
                                                $getUserData->execute([$newid]);

                                                if ($getUserData->rowCount() > 0) {

                                                    // fetch new user information
                                                    $u = $getUserData->fetch();

                                                    // create session
                                                    $login->createCookie($token, $serial);
                                                    $login->createSession($u, $token, $serial, 0);

                                                    // return success for the customer
                                                    $return->status = true;
                                                    $return->message = 'Du hast Dich erfolgreich registriert. Eine E-Mail zur Bestätigung wurde an <span style="color:#F1D394;"><strong>' . $inputmail . '</strong></span> gesendet!';

                                                    exit(json_encode($return));
                                                } else {
                                                    $return->message = "Du kannst dich jetzt mit deinem neuen Profil einloggen!";
                                                    exit(json_encode($return));
                                                }
                                            } else {
                                                $return->message = "Es konnte keine Verifizierungsmail versendet werden. Bitte nutze unser <a href='/contact' target='_blank'>Kontaktformular</a>, um deine Registrierung abzuschließen";
                                                exit(json_encode($return));
                                            }
                                        } else {
                                            exit(json_encode($return));
                                        }
                                    } else {
                                        exit(json_encode($return));
                                    }
                                } else {
                                    exit(json_encode($return));
                                }
                            } else {
                                exit(json_encode($return));
                            }
                        } else {
                            $return->message = 'Deine E-Mail hat ein falsches Format. Bitte nutze name@host.endung!';
                            exit(json_encode($return));
                        }
                    } else {
                        $return->message = 'Der Captcha-Code scheint falsch zu sein, versuche es erneut';
                        exit(json_encode($return));
                    }
                } else {
                    $return->message = 'Bitte wähle ein Passwort zwischen 8 und 32 Zeichen';
                    exit(json_encode($return));
                }
            } else {
                $return->message = 'Die gewählten Passwörter stimmen nicht überein';
                exit(json_encode($return));
            }
        } else {
            $return->message = 'Bitte akzeptiere unsere <a href="/intern/index" target="_blank">AGB</a> und <a href="#" target="_blank">Datenschutzerklärung</a>!';
            exit(json_encode($return));
        }
    } else {
        $return->message = 'Bitte akzeptiere die <a href="/intern/index#dsg-general-cookies" target="_blank">Cookie-Bedingungen</a>, um fortzufahren';
        exit(json_encode($return));
    }
} else {
    $return->message = 'Bitte fülle alle Felder aus';
    exit(json_encode($return));
}
