<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";

if (
    isset($_REQUEST['id'])
    && is_numeric($_REQUEST['id'])
    && $loggedIn
    && $user['admin'] === '1'
) {

    // CLEAR VARS
    $id = htmlspecialchars($_REQUEST['id']);

    // CHECK IF COURSE EXISTS
    $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // COURSE QUERY
        $s = $sr->fetch();

        // SET CASES FOR INSERTION
        if ($s['active'] === '0') {
            $active = '1';
            $exit = 'on';
        } else {
            $active = '0';
            $exit = 'off';
        }

        // INSERTION
        $upd = $pdo->prepare("UPDATE courses SET active = ?, updated = ? WHERE id = ?");
        $upd->bind_param('sss', $active, $timestamp, $id);
        $upd->execute();

        if ($upd) {
            $pdo->commit();
            $pdo->close();
            exit($exit);
        } else {
            $pdo->rollback();
            $pdo->close();
            exit('0');
        }
    } else {
        exit('1');
    }
} else {
    exit;
}
