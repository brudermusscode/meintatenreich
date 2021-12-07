<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "set" => NULL,
    "message" => "Da ist wohl ein Oopsie passiert"
];

// objectify return array
$return = (object) $return;

// valid status?es?
$validStatus = ['got', 'sent', 'done', 'canceled'];

if (
    isset($_REQUEST['status'], $_REQUEST['id']) &&
    $admin->isAdmin() &&
    in_array($_REQUEST['status'], $validStatus)
) {

    // variablize
    $id = $_REQUEST['id'];
    $st = $_REQUEST['status'];

    // set autocommiting to false
    $commit = true;

    // CHECK ORDER EXISTENCE
    $getOrder = $pdo->prepare("SELECT * FROM customer_buys, customer WHERE customer_buys.uid = customer.id AND customer_buys.id = ?");
    $getOrder->execute([$id]);

    if ($getOrder->rowCount() > 0) {

        // check for status and set commiting
        if ($st == "canceled") {

            // set autocommiting to false
            $commit = false;
        }

        // variablize
        $s = $getOrder->fetch();
        $to = $s->mail;
        $oi = $s->orderid;

        // begin mysql transaction
        $pdo->beginTransaction();

        // update order's status and updated timestamp
        $updateOrder = $pdo->prepare("UPDATE customer_buys SET status = ?, updated = CURRENT_TIMESTAMP WHERE id = ?");
        $updateOrder = $shop->tryExecute($updateOrder, [$st, $id], $pdo, $commit);

        if ($updateOrder->status) {

            // if status was set to canceled or done, we need to care about
            // products inside of the order and their reservations
            if (!$commit) {

                // set commiting to true
                $commit = true;

                $updateProducts = $pdo->prepare("
                    UPDATE products, customer_buys, customer_buys_products 
                    SET products.available = '1' 
                    WHERE customer_buys_products.bid = customer_buys.id
                    AND customer_buys_products.pid = products.id
                    AND customer_buys.id = ?
                ");
                $updateProducts = $shop->tryExecute($updateProducts, [$id], $pdo, true);

                if (!$updateOrder->status) {
                    $return->message = "error updating products";
                    exit(json_encode($return));
                }
            }

            $return->status = true;
            $return->set = $st;
            $return->message = "Status aktualisiert";
            exit(json_encode($return));
        } else {
            $return->message = "[1] order error";
            exit(json_encode($return));
        }
    } else {
        $return->message = "Diese Bestellung scheint nicht zu existieren";
        exit(json_encode($return));
    }
} else {
    $return->message = "[1] hurensohn";
    exit(json_encode($return));
}
