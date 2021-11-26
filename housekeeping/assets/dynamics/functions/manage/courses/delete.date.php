<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";

if (
    isset($_REQUEST['id'], $_REQUEST['couid'])
    && is_numeric($_REQUEST['id'])
    && is_numeric($_REQUEST['couid'])
    && $loggedIn
    && $user['admin'] === '1'
) {

    // CLEAR VARS
    $id = htmlspecialchars($_REQUEST['id']);
    $couid = htmlspecialchars($_REQUEST['couid']);

    // CHECK IF COURSE EXISTS
    $sel = $c->prepare("SELECT * FROM courses WHERE id = ?");
    $sel->bind_param('s', $couid);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // CHECK IF DATE EXISTS
        $sel = $c->prepare("SELECT * FROM courses_dates WHERE id = ?");
        $sel->bind_param('s', $id);
        $sel->execute();
        $sr = $sel->get_result();
        $sel->close();

        if ($sr->rowCount() > 0) {

            // INSERTION
            $upd = $c->prepare("UPDATE courses_dates SET deleted = '1', updated = ? WHERE id = ?");
            $upd->bind_param('ss', $timestamp, $id);
            $upd->execute();

            if ($upd) {
                $c->commit();
                $c->close();
                exit('success');
            } else {
                $c->rollback();
                $c->close();
                exit('0');
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
