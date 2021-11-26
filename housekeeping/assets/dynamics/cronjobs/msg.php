<?php


// ERROR CODE :: 0
require_once "../../../../mysql/_.session.php";

if ($loggedIn && $user['admin'] === '1') {

    $sel = $c->prepare("SELECT * FROM admin_mails_settings WHERE id = '1'");
    $sel->execute();
    $sr = $sel->get_result();
    $lastChecked = $sr->fetch_assoc();
    $sel->close();

    $last = strtotime($lastChecked['timestamp']);
    $ts = [];
    $sel = $c->prepare("SELECT * FROM admin_mails_got");
    $sel->bind_param('s', $last);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();
    while ($abc = $sr->fetch_assoc()) {
        $ts[] = $abc['timestamp'];
    }

    $hasNew = false;
    foreach ($ts as $t) {
        if (($last - strtotime($t)) < 0) {
            $hasNew = true;
        }
    }

    if ($hasNew) {

        // UPDATE
        $upd = $c->prepare("UPDATE admin_mails_settings SET mails_checked = '0' WHERE id = '1'");
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
        exit('0');
    }
} else {
    exit;
}
