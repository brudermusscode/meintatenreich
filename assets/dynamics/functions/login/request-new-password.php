<?php

// ERROR CODE :: 0

require_once "../../../../mysql/_.session.php";

if (
    isset($_REQUEST['action'], $_REQUEST['id'], $_REQUEST['value'], $_REQUEST['password'], $_REQUEST['password2'])
    && $_REQUEST['action'] === 'request-new-password'
    && preg_match('/^[a-zA-Z0-9]+$/', $_REQUEST['value'])
    && is_numeric($_REQUEST['id'])
    && $_REQUEST['password'] !== ''
    && $_REQUEST['password2'] !== ''
) {

    $val = htmlspecialchars($_REQUEST['value']);
    $uid = htmlspecialchars($_REQUEST['id']);
    $pw1 = md5($_REQUEST['password']);
    $pw2 = md5($_REQUEST['password2']);

    // CHECK FGP EXISTENCE
    $sel = $c->prepare("SELECT * FROM customer_password_forgot WHERE uid = ? AND value = ?");
    $sel->bind_param('ss', $uid, $val);
    $sel->execute();
    $s_r = $sel->get_result();

    if ($s_r->rowCount() > 0) {

        $sel->close();

        if ($pw1 === $pw2) {

            if (strlen($pw1) >= 8) {

                if (preg_match('/^[a-zA-Z0-9=.,_\-+*#~?!&%$§\/]+$/', $pw1)) {

                    // CHECK USER EXISTENCE
                    $sel = $c->prepare("SELECT * FROM customer WHERE id = ?");
                    $sel->bind_param('s', $uid);
                    $sel->execute();
                    $s_r = $sel->get_result();

                    if ($s_r->rowCount() > 0) {

                        $sel->close();

                        // UPDATE CUSTOMER
                        $upd = $c->prepare("UPDATE customer SET password = ? WHERE id = ?");
                        $upd->bind_param('ss', $pw1, $uid);
                        $upd->execute();

                        // DELETE FGP
                        $del = $c->prepare("DELETE FROM customer_password_forgot WHERE uid = ? AND value = ?");
                        $del->bind_param('ss', $uid, $val);
                        $del->execute();

                        if ($upd && $del) {
                            $c->commit();
                            $c->close();
                            $upd->close();
                            $del->close();
                            exit('success');
                        } else {
                            $c->rollback();
                            $c->close();
                            $upd->close();
                            $del->close();
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
    exit;
}
