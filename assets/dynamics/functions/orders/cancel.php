<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!"
];

// objectify response array
$return = (object) $return;

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'request-cancel-order'
    && is_numeric($_REQUEST['id'])
) {

    // variablize
    $id = $_REQUEST['id'];
    $uid = $my->id;

    // check order existence and source
    $getOrder = $pdo->prepare("SELECT * FROM customer_buys WHERE uid = ? AND id = ?");
    $getOrder->execute([$uid, $id]);

    if ($getOrder->rowCount() > 0) {

        // fetch order information
        $o = $getOrder->fetch();

        // check if order still is cancable
        if ($o->cancability == '1') {

            // start mysql transaction
            $pdo->beginTransaction();

            // update order to disabled
            $updateOrder = $pdo->prepare("UPDATE customer_buys SET status = 'canceled', cancability = '0', updated = CURRENT_TIMESTAMP WHERE uid = ? AND id = ?");
            $updateOrder = $shop->tryExecute($updateOrder, [$uid, $id], $pdo, false);

            if ($updateOrder->status) {

                // update order products and set to available again
                $update = $pdo->prepare("
                    UPDATE customer_buys, customer_buys_products, products 
                    SET products.available = '1'
                    WHERE customer_buys.id = customer_buys_products.bid
                    AND customer_buys_products.pid = products.id 
                    AND customer_buys.id = ?
                    AND customer_buys.uid = ?
                ");
                $update = $shop->tryExecute($update, [$id, $uid], $pdo, true);

                if ($update->status) {

                    $return->status = true;
                    $return->message = "Deine Bestellung wurde storniert";

                    exit(json_encode($return));
                } else {
                    exit(json_encode($return));
                }
            } else {
                exit(json_encode($return));
            }
        } else {
            $return->message = "Diese Bestellung kann leider nicht mehr storniert werden";
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
