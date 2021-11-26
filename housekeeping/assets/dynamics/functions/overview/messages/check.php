<?php

require_once "../../../../../../mysql/_.session.php";

if ($loggedIn && $user['admin'] === '1') {

    $id = $config["mails_set_id"];

    // CHECK IF CHECKED
    $sel = $c->prepare("SELECT * FROM admin_mails_settings WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount()) {

        $s = $sr->fetch_assoc();

        if ($s['mails_checked'] === '0') {

            // UPDATE SET CHECKED
            $upd = $c->prepare("UPDATE admin_mails_settings SET mails_checked = '1', timestamp = ? WHERE id = ?");
            $upd->bind_param('ss', $timestamp, $id);
            $upd->execute();

            if ($upd) {
                $c->commit();
                $c->close();
                exit('1');
            } else {
                $c->rollback();
                $c->close();
                exit('0');
            }
        } else {
            exit('1');
        }
    } else {
        exit('0');
    }
} else {
    exit;
}
