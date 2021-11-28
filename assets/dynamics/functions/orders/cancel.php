<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'request-cancel-order'
    && is_numeric($_REQUEST['id'])
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

        // check if order still is cancable
        if ($o->cancability == '1') {

            // add debug-
            $debugArray["orderCancable"] = true;

            // get products from order
            $getOrderProducts = $pdo->prepare("SELECT pid FROM customer_buys_products WHERE bid = ?");
            $getOrderProducts->execute([$id]);

            $updateOrder = false;
            $deleteReservations = false;

            if ($getOrderProducts->rowCount() > 0) {

                // add debug
                $looped = 0;
                $debugArray["orderProductsExist"] = true;

                // create array of products
                $products = [];
                foreach ($getOrderProducts->fetchAll() as $op) {
                    $products[] = (int)$op->pid;

                    // remove reservations for all products
                    $deleteReservations = $pdo->prepare("DELETE FROM products_reserved WHERE uid = ? AND pid = ?");
                    $deleteReservations->execute([$uid, $op->pid]);

                    if ($deleteReservations) {
                        $looped++;
                    }
                }

                // add debug
                $debugArray["orderProductsReservationsDeleted"] = $looped . "/" . $getOrderProducts->rowCount();
            }

            // update order to diabled
            $updateOrder = $pdo->prepare("UPDATE customer_buys SET status = 'canceled', cancability = '0', updated = CURRENT_TIMESTAMP WHERE uid = ? AND id = ?");
            $updateOrder->execute([$uid, $id]);

            if ($updateOrder && $deleteReservations) {

                $pdo->commit();
                exit(json_encode($debugArray));
            } else {

                $pdo->rollback();
                exit('0');
            }
        } else {
            exit('2'); // not cancable
        }
    } else {
        exit('1'); // order doesn't exist/not owner
    }
} else {
    exit("0");
}
