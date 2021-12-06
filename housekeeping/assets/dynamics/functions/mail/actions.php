<?php

require_once "../../../../../../mysql/_.session.php";

if (
    isset($_REQUEST['isread'], $_REQUEST['fav'], $_REQUEST['id'])
    && $loggedIn
    && $user['admin'] === '1'
) {

    $isread = $_REQUEST['isread'];
    $fav = $_REQUEST['fav'];
    $id = $_REQUEST['id'];

    if ($isread !== '1' && $isread !== '0') {
        $isread = '1';
    }

    if ($fav !== '1' && $fav !== '0') {
        $fav = '1';
    }

    // SELECT: PARAMS, NO FETCH, CHECK nUM ROWS
    $sel = $pdo->prepare("SELECT * FROM admin_mails_got WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // UPDATE
        $upd = $pdo->prepare("UPDATE admin_mails_got SET isread = ?, fav = ? WHERE id = ?");
        $upd->bind_param('sss', $isread, $fav, $id);
        $upd->execute();

        if ($upd) {
            $pdo->commit();
            $pdo->close();
            exit('success');
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
