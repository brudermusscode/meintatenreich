<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (
    isset($_REQUEST['oldpass'], $_REQUEST['newpass'], $_REQUEST['newpass2'])
    && $_REQUEST['oldpass'] !== ''
    && $_REQUEST['newpass'] !== ''
    && $_REQUEST['newpass2'] !== ''
    && $loggedIn
) {

    // VARS
    $oldpass = md5($_REQUEST['oldpass']);
    $newpass = $_REQUEST['newpass'];
    $newpass2 = $_REQUEST['newpass2'];
    $uid = $my->id;

    // CHECKS
    if ($newpass === $newpass2) {

        if (md5($newpass) !== $user['password']) {

            if ($oldpass === $user['password']) {

                if (strlen($newpass) >= 8) {

                    if (preg_match('/^[a-zA-Z0-9=.,_\-+*#~?!&%$§\/]+$/', $newpass)) {

                        // MD5ify
                        $newpass = md5($_REQUEST['newpass']);
                        $newpass2 = md5($_REQUEST['newpass2']);

                        $update = $c->prepare("UPDATE customer SET password = ? WHERE id = ?");
                        $update->bind_param('ss', $newpass, $uid);
                        $update->execute();

                        $insert = $c->prepare("INSERT INTO customer_password_changes (uid, password, timestamp) VALUES (?,?,?)");
                        $insert->bind_param('sss', $uid, $newpass, $timestamp);
                        $insert->execute();

                        if ($update && $insert) {
                            $c->commit();
                            $c->close();
                            $insert->close();
                            $update->close();
                            exit('success');
                        } else {
                            $c->rollback();
                            $c->close();
                            $insert->close();
                            $update->close();
                            exit('0');
                        }
                    } else {
                        exit('5'); // Invalid characters
                    }
                } else {
                    exit('4'); // New password too short
                }
            } else {
                exit('3'); // Old password not matching
            }
        } else {
            exit('2'); // Passwords are the same
        }
    } else {
        exit('1'); // Passwords not matching
    }
} else {

    exit;
}
