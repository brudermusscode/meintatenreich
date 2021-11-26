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
    $sel = $c->prepare("SELECT * FROM customer_buys WHERE id = ? AND uid = ? AND status != 'done'");
    $sel->bind_param('ss', $id, $uid);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount()) {

        $pids = [];
        $selProducts = $c->prepare("SELECT pid FROM customer_buys_products WHERE bid = ?");
        $selProducts->bind_param('s', $id);
        $selProducts->execute();
        $selProducts_r = $selProducts->get_result();
        $selProducts->close();
        while ($p = $selProducts_r->fetch_assoc()) {
            $pids[] = $p['pid'];
        }

        $updProducts = true;
        $delRes = true;
        foreach ($pids as $pid) {

            // MAKE PRODUCTS UNAVAILABLE
            $updProducts = $c->prepare("UPDATE products SET available = '0' WHERE id = ?");
            $updProducts->bind_param('s', $pid);
            $updProducts->execute();

            // REMOVE RESERVATIONS
            $delRes = $c->prepare("DELETE FROM products_reserved WHERE pid = ?");
            $delRes->bind_param('s', $pid);
            $delRes->execute();
        }

        // UPDATE ORDER
        $upd = $c->prepare("UPDATE customer_buys SET status = 'done' WHERE id = ? AND uid = ?");
        $upd->bind_param('ss', $id, $uid);
        $upd->execute();

        if ($upd && $updProducts && $delRes) {
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
