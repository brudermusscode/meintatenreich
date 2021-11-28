<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])
) {

    // create debugging array
    $debugArray = [];

    // variablize
    $id = $_REQUEST['id'];
    $uid = $my->id;

    // check order existence and source
    $getOrder = $pdo->prepare("SELECT * FROM customer_buys WHERE uid = ? AND id = ?");
    $getOrder->execute([$uid, $id]);

    if ($getOrder->rowCount() > 0) {

        // add debug-
        $debugArray["orderExists"] = true;

        // fetch order information
        $o = $getOrder->fetch();

        $pids = [];
        $updateProducts = false;
        $deleteReservations = false;
        $loopedProducts = 0;
        $loopedReservations = 0;

        $getOrderProducts = $pdo->prepare("SELECT pid FROM customer_buys_products WHERE bid = ?");
        $getOrderProducts->execute([$id]);

        foreach ($getOrderProducts->fetchAll() as $op) {
            $pids[] = $op->pid;

            $updateProducts = $pdo->prepare("UPDATE products SET available = '0' WHERE id = ?");
            $updateProducts->execute([$op->pid]);

            if ($updateProducts) {
                $loopedProducts++;
            }

            $deleteReservations = $pdo->prepare("DELETE FROM products_reserved WHERE pid = ?");
            $deleteReservations->execute([$op->pid]);

            if ($deleteReservations) {
                $loopedReservations++;
            }
        }

        // add debug
        $debugArray["orderProductsUpdated"] = $loopedProducts . "/" . $getOrderProducts->rowCount();
        $debugArray["orderProductsReservationsDeleted"] = $loopedReservations . "/" . $getOrderProducts->rowCount();

        // UPDATE ORDER
        $updateOrder = $pdo->prepare("UPDATE customer_buys SET status = 'done', updated = CURRENT_TIMESTAMP WHERE id = ? AND uid = ?");
        $updateOrder->execute([$id, $uid]);

        if ($updateOrder && $updateProducts && $deleteReservations) {

            $pdo->commit();
            exit(json_encode($debugArray));
        } else {

            $pdo->rollback();
            exit('0');
        }
    } else {
        exit('1'); // order does not exist
    }
} else {
    exit("0");
}
