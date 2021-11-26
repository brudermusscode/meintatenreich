<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['oldpass'], $_REQUEST['newpass'], $_REQUEST['newpass2'])
    && $_REQUEST['oldpass'] !== ''
    && $_REQUEST['newpass'] !== ''
    && $_REQUEST['newpass2'] !== ''
    && $loggedIn
) {

    // variablize
    $inputPasswordOld = $_REQUEST['oldpass'];
    $inputPasswordNew = $_REQUEST['newpass'];
    $inputPasswordNew2 = $_REQUEST['newpass2'];
    $uid = $my->id;

    // check if old entered password matches the current one
    if (password_verify($inputPasswordOld, $my->password)) {

        // check for equality between both passwords
        if ($inputPasswordNew === $inputPasswordNew2) {

            // check if current password is equal to new
            if (!password_verify($inputPasswordNew, $my->password)) {

                // check if the password is higher in length than x symbols
                if (strlen($inputPasswordNew) >= 8) {

                    // validate new password
                    if (!preg_match('/[^a-z0-9=.,_\-+*#~?!&%$§]/i', $inputPasswordNew)) {

                        // encrypt new password
                        $newPassword = password_hash($inputPasswordNew, PASSWORD_DEFAULT);

                        // update current password
                        $update = $pdo->prepare("UPDATE customer SET password = ? WHERE id = ?");
                        $update->execute([$newPassword, $uid]);

                        // insert password change
                        $insert = $pdo->prepare("INSERT INTO customer_password_changes (uid, password) VALUES (?,?)");
                        $insert->execute([$uid, $newPassword]);

                        if ($update && $insert) {

                            $pdo->commit();
                            exit('success');
                        } else {

                            $pdo->rollback();
                            exit('0');
                        }
                    } else {
                        exit('5'); // Invalid characters
                    }
                } else {
                    exit('4'); // new password too short
                }
            } else {
                exit('3'); // used old password for new
            }
        } else {
            exit('2'); // passwords not matching
        }
    } else {
        exit('1'); // old password is wrong
    }
} else {

    exit("0");
}
