<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (isset($_REQUEST['action'])) {

    // create debug array
    $debugArray = [];

    $action = $_REQUEST["action"];

    // check action
    if ($action == "forgot-password" && isset($_REQUEST['mail']) && !empty($_REQUEST['mail'])) {


        $inputmail = htmlspecialchars($_REQUEST['mail']);

        // validate email format
        if (filter_var($inputmail, FILTER_VALIDATE_EMAIL)) {

            // check for user existence
            $getCustomer = $pdo->prepare("SELECT * FROM customer WHERE mail = ?");
            $getCustomer->execute([$inputmail]);

            if ($getCustomer->rowCount() > 0) {

                // fetch user information
                $c = $getCustomer->fetch();
                $uid = $c->id;

                // create random key for password verification
                $value = $login->createString(64);

                // check if password key already exists
                $getPasswordForgot = $pdo->prepare("SELECT * FROM customer_password_forgot WHERE uid = ?");
                $getPasswordForgot->execute([$uid]);

                $updatePasswortForgot = false;
                $insertPasswordForgot = false;

                if ($getPasswordForgot->rowCount() > 0) {

                    // there's a key, so update that one
                    $updatePasswortForgot = $pdo->prepare("UPDATE customer_password_forgot SET value = ? WHERE uid = ?");
                    $updatePasswortForgot->execute([$value, $uid]);
                } else {

                    // no key tho, insert a new one
                    $insertPasswordForgot = $pdo->prepare("INSERT INTO customer_password_forgot (uid, value) VALUES (?,?)");
                    $insertPasswordForgot->execute([$uid, $value]);
                }

                // prepare verification mail
                $mailsubject = $mail['subjectForgotPassword'];

                $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/forgot-password.html');
                $mailbody = str_replace('%url%', $url["main"] . "/my/forgot-password?id=" . $uid . "&value=" . $value, $mailbody);

                $mailheader  = $mail['header'];

                if (
                    ($updatePasswortForgot || $insertPasswordForgot) &&
                    mail($inputmail, $mailsubject, $mailbody, $mailheader)
                ) {

                    $pdo->commit();
                    exit('success');
                } else {

                    $pdo->rollback();
                    exit('0');
                }
            } else {
                exit('2');
            }
        } else {
            exit("0");
        }

        // request a new password
    } else if (
        $action == "forgot-password-2" &&
        isset($_REQUEST['id'], $_REQUEST['value'], $_REQUEST['password'], $_REQUEST['password2'])
        && preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['value'])
        && is_numeric($_REQUEST['id'])
        && $_REQUEST['password'] !== ''
        && $_REQUEST['password2'] !== ''
    ) {


        $val = htmlspecialchars($_REQUEST['value']);
        $uid = htmlspecialchars($_REQUEST['id']);
        $pw1 = $_REQUEST['password'];
        $pw2 = $_REQUEST['password2'];

        // check for key existence
        $getPasswordForgot = $pdo->prepare("SELECT * FROM customer_password_forgot WHERE uid = ? AND value = ?");
        $getPasswordForgot->execute([$uid, $val]);

        if ($getPasswordForgot->rowCount() > 0) {

            // add debug-
            $debugArray["keyExists"] = true;

            // check if both passwords match
            if ($pw1 === $pw2) {

                // add debug-
                $debugArray["passwordsMatch"] = true;

                // check if password is higher in length than x letters
                if (strlen($pw1) >= 8) {

                    // add debug-
                    $debugArray["passwordsLength"] = true;

                    // validate password
                    if ($shop->validatePassword($pw1)) {

                        // add debug-
                        $debugArray["passwordsValidation"] = true;

                        // check for user existence
                        $getCustomer = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
                        $getCustomer->execute([$uid]);

                        if ($getCustomer->rowCount() > 0) {

                            // add debug-
                            $debugArray["customerExists"] = true;

                            // create password hash
                            $passwordHashed = password_hash($pw1, PASSWORD_DEFAULT);

                            // UPDATE CUSTOMER
                            $updateCustomer = $pdo->prepare("UPDATE customer SET password = ? WHERE id = ?");
                            $updateCustomer->execute([$passwordHashed, $uid]);

                            // DELETE FGP
                            $deletePasswordForgot = $pdo->prepare("DELETE FROM customer_password_forgot WHERE uid = ? AND value = ?");
                            $deletePasswordForgot->execute([$uid, $val]);

                            if ($updateCustomer && $deletePasswordForgot) {

                                // add debug-
                                $debugArray["success"] = true;

                                $pdo->commit();
                                exit(json_encode($debugArray));
                            } else {

                                $pdo->rollback();
                                exit('0');
                            }
                        } else {
                            exit('5');
                        }
                    } else {
                        exit('4');
                    }
                } else {
                    exit('3');
                }
            } else {
                exit('2');
            }
        } else {
            exit('1');
        }
    } else {
        exit("0");
    }
} else {
    exit("0");
}
