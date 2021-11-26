<?php

// ERROR CODE :: 0

include_once '../../../../mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'request-cancel-order'
    && is_numeric($_REQUEST['id'])
) {

    $id = $_REQUEST['id'];
    $uid = $my->id;

    // CHECK ORDER ACCESSABILITY
    $sel = $c->prepare("SELECT * FROM customer_buys WHERE uid = ? AND id = ?");
    $sel->bind_param('ss', $uid, $id);
    $sel->execute();
    $s_r = $sel->get_result();

    if ($s_r->rowCount() > 0) {

        $s = $s_r->fetch_assoc();
        $sel->close();

        // CHECK CANCABILITY
        if ($s['cancability'] === '1') {

            // GET PRODUCTS FROM SCARD
            $sel = $c->prepare("SELECT pid FROM customer_buys_products WHERE bid = ?");
            $sel->bind_param('s', $id);
            $sel->execute();
            $s_r = $sel->get_result();

            if ($s_r->rowCount() > 0) {

                // MAKE ARRAY OF PRODUCTS
                $products = [];
                while ($prd = $s_r->fetch_assoc()) {
                    $products[] = (int)$prd['pid'];
                }
            }
            $sel->close();

            foreach ($products as $pid) {

                // REMOVE RESERVATIONS
                $delRes = $c->prepare("DELETE FROM products_reserved WHERE uid = ? AND pid = ?");
                $delRes->bind_param('ss', $uid, $pid);
                $delRes->execute();
            }

            // CANCEL ORDER
            $upd = $c->prepare("UPDATE customer_buys SET status = 'canceled', cancability = '0' WHERE uid = ? AND id = ?");
            $upd->bind_param('ss', $uid, $id);
            $upd->execute();

            if ($upd && $delRes) {
                $c->commit();
                $upd->close();
                $delRes->close();
                $c->close();
                exit('success');
            } else {
                $c->rollback();
                $upd->close();
                $delRes->close();
                $c->close();
                exit('0');
            }
        } else {
            exit('2'); // CANCABILITY
        }
    } else {
        exit('1'); // ORDER NOT YOURS/DOESN'T EXIST
    }
} else {
    exit;
}
