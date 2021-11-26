<?php

// ERROR CODE :: 0

include_once '../../../../mysql/_.session.php';

if (
    isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])
) {

    $id = $_REQUEST['id'];
    $uid = $my->id;
    $res = [];

    // CHECK EXISTENCE OF ORDER
    $sel = $c->prepare("SELECT * FROM customer_buys WHERE id = ? AND uid = ?");
    $sel->bind_param('ss', $id, $uid);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount()) {

        // UPDATE ORDER
        $upd = $c->prepare("UPDATE customer_buys SET paid = '1' WHERE id = ? AND uid = ?");
        $upd->bind_param('ss', $id, $uid);
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
        exit('1');
    }
} else {
    exit;
}
