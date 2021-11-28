<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// strat mysql transactions
$pdo->beginTransaction();

// create debug array
$debugArray = [];

if (
    isset($_REQUEST["mail"], $_REQUEST["password"], $_REQUEST["password2"], $_REQUEST["g-recaptcha-response"])
    && !$loggedIn
) {

    // add debug-
    $debugArray["requestComplete"] = true;

    // check if cookies are accepted
    if (isset($_COOKIE['cookies']) && $_COOKIE['cookies'] === 'true') {

        // add debug-
        $debugArray["cookiesAccepted"] = true;

        // check if disclaimer box is checked
        if (isset($_REQUEST["agb"]) && $_REQUEST["agb"] === 'on') {

            // add debug-
            $debugArray["disclaimerAccepted"] = true;

            // variablize
            $agb = htmlspecialchars($_REQUEST["agb"]);
            $inputmail = htmlspecialchars($_REQUEST["mail"]);
            $password = $_REQUEST["password"];
            $password2 = $_REQUEST["password2"];
            $remoteaddr = $_SERVER['REMOTE_ADDR'];
            $captcha = $_REQUEST["g-recaptcha-response"];

            // check password matching
            if ($password === $password2) {

                // add debug-
                $debugArray["passwordsMatch"] = true;

                // check for password range
                if (strlen($_POST["password"]) >= 8 && strlen($_POST["password"]) <= 32) {

                    // add debug-
                    $debugArray["passwordValid"] = true;

                    // check for grecaptcha
                    $captchaResponse = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $conf["recaptcha_privatekey"] . "&response=" . $captcha . "&remoteip=" . $remoteaddr));
                    if ($captchaResponse->success) {

                        // add debug-
                        $debugArray["gRecaptchaValid"] = true;

                        // check email format
                        if (filter_var($inputmail, FILTER_VALIDATE_EMAIL)) {

                            // add debug-
                            $debugArray["emailValid"] = true;

                            // check on mail
                            $getAllMail = $pdo->prepare("SELECT * FROM customer WHERE mail = ?");
                            $getAllMail->execute([$inputmail]);
                            $getAllMail->fetch();

                            // check for email existence
                            if ($getAllMail->rowCount() < 1) {

                                // add debug-
                                $debugArray["emailInUse"] = false;

                                $httpxfor = $login->get_client_ip();
                                $key = $login->createString(64);
                                $displayname = 'customer-' . $login->createString(12);

                                $password = password_hash($password, PASSWORD_DEFAULT);

                                // insert new customer
                                $insertCustomer = $pdo->prepare("
                                    INSERT INTO customer (displayname, mail, password, remoteaddr, httpx) 
                                    VALUES (?,?,?,?,?)
                                ");
                                $insertCustomer->execute([$displayname, $inputmail, $password, $remoteaddr, $httpxfor]);

                                // create login
                                $newid = $pdo->lastInsertId();
                                $token = $login->createString(64);
                                $serial = $login->createString(64);

                                // create session
                                $create_session = $pdo->prepare("INSERT INTO system_sessions (uid,token,serial,remoteaddr,httpx) VALUES (?,?,?,?,?)");
                                $create_session->execute([$newid, $token, $serial, $remoteaddr, $httpxfor]);

                                // insert admin log
                                $insertAdminLog = $pdo->prepare("INSERT INTO admin_overview (rid, ttype) VALUES (?,'customer')");
                                $insertAdminLog->execute([$newid]);

                                // create verification key
                                $insertVerification = $pdo->prepare("INSERT INTO customer_verifications (uid, vkey) VALUES (?,?)");
                                $insertVerification->execute([$newid, $key]);

                                // prepare verification mail
                                $mailsubject = $mail['subjectSignup'];

                                $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/signup.html');
                                $mailbody = str_replace('%mail%', $inputmail, $mailbody);
                                $mailbody = str_replace('%url%', $url["main"] . "/my/verification?id=" . $newid . "&key=" . $key, $mailbody);

                                $mailheader  = $mail['header'];

                                // check insertions
                                if (
                                    $insertCustomer && $create_session && $insertAdminLog && $insertVerification &&
                                    mail($inputmail, $mailsubject, $mailbody, $mailheader)
                                ) {

                                    // add debug-
                                    $debugArray["success"] = true;

                                    $login->createCookie($newid, $displayname, $token, $serial);
                                    $login->createSession($newid, $displayname, $token, $serial);

                                    $pdo->commit();
                                    exit(json_encode($debugArray));
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
