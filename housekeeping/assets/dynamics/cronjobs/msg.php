<?php


// ERROR CODE :: 0
require_once "../../../../mysql/_.session.php";

if ($admin->isAdmin()) {

    $sel = $pdo->prepare("SELECT * FROM admin_mails_settings WHERE id = '1'");
    $sel->execute();
    $sr = $sel->get_result();
    $lastChecked = $sr->fetch();
    $sel->close();

    $last = strtotime($lastChecked['timestamp']);
    $ts = [];
    $sel = $pdo->prepare("SELECT * FROM admin_mails_got");
    $sel->bind_param('s', $last);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();
    foreach ($abc = $sr->fetchAll() as ) {
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
        $upd = $pdo->prepare("UPDATE admin_mails_settings SET mails_checked = '0' WHERE id = '1'");
        $upd->execute();

        if ($upd) {
            $pdo->commit();
            $pdo->close();
            exit('1');
        } else {
            $pdo->rollback();
            $pdo->close();
            exit('0');
        }
    } else {
        exit('0');
    }
} else {
    exit;
}
