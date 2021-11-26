<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$pdo->beginTransaction();

if (
    isset($_REQUEST["mail"], $_REQUEST["password"], $_REQUEST["password2"], $_REQUEST["g-recaptcha-response"])
    && !$loggedIn
) {

    if (isset($_COOKIE['cookies']) && $_COOKIE['cookies'] === 'true') {

        if (isset($_REQUEST["agb"]) && $_REQUEST["agb"] === 'on') {

            // variablize
            $agb = htmlspecialchars($_REQUEST["agb"]);
            $mail = htmlspecialchars($_REQUEST["mail"]);
            $password = $_REQUEST["password"];
            $password2 = $_REQUEST["password2"];
            $remoteaddr = $_SERVER['REMOTE_ADDR'];
            $captcha = $_REQUEST["g-recaptcha-response"];

            // check password matching
            if ($password === $password2) {

                // check for password range
                if (strlen($_POST["password"]) >= 8 && strlen($_POST["password"]) <= 32) {

                    // check for grecaptcha
                    $captchaResponse = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $conf["recaptcha_privatekey"] . "&response=" . $captcha . "&remoteip=" . $remoteaddr));
                    if ($captchaResponse->success) {

                        // check email format
                        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

                            // check on mail
                            $getAllMail = $pdo->prepare("SELECT * FROM customer WHERE mail = ?");
                            $getAllMail->execute([$mail]);
                            $getAllMail->fetch();

                            // check for email existence
                            if ($getAllMail->rowCount() < 1) {

                                $httpxfor = $login->get_client_ip();
                                $key = $login->createString(64);
                                $displayname = 'customer-' . $login->createString(12);

                                // insert new customer
                                $insertCustomer = $pdo->prepare("
                                    INSERT INTO customer (displayname, mail, password, verification_key, remoteaddr, httpx) 
                                    VALUES (?,?,?,?,?,?)
                                ");
                                $insertCustomer->execute([$displayname, $mail, $password, $key, $remoteaddr, $httpxfor]);

                                // create login
                                $newid = $pdo->lastInsertId();
                                $token = $login->createString(64);
                                $serial = $login->createString(64);

                                // create session
                                $create_session = $pdo->prepare("INSERT INTO system_sessions (uid,token,serial,remoteaddr,httpx) VALUES (?,?,?,?,?)");
                                $create_session->execute([$newid, $token, $serial, $remoteaddr, $httpxfor]);

                                // insert admin log
                                $insertAdminLog = $pdo->prepare("INSERT INTO admin_overview (rid, ttype, timestamp) VALUES (?,'customer',?)");
                                $insertAdminLog->execute([$newid, $timestamp]);

                                // PREPARE VERIFICATION MAIL
                                /* $mailbody = file_get_contents('../assets/templates/mail/register.html');
                                $mailbody = str_replace('%mail%', $inputmail, $mailbody);
                                $mailbody = str_replace('%url%', $purl . "/verify?id=" . $newid . "&key=" . $key, $mailbody);
                                $mailsubject = $config['mail_reg_subject'];
                                $mailheader  = $config['mail_header']; */

                                // check insertions
                                if (
                                    $insertCustomer && $create_session && $insertAdminLog
                                    //mail($inputmail, $mailsubject, $mailbody, $mailheader)
                                ) {

                                    $login->createCookie($newid, $displayname, $token, $serial);
                                    $login->createSession($newid, $displayname, $token, $serial);

                                    $pdo->commit();
                                    exit("success");
                                } else {

                                    $pdo->rollback();
                                    exit("0");
                                }
                            } else {
                                exit('7');
                            } // mail does exist

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
