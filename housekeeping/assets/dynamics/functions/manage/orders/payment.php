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
$validStatus = [0, 1, 2];

if (
    isset($_REQUEST['status'], $_REQUEST['id']) &&
    in_array($_REQUEST['status'], $validStatus) &&
    $admin->isAdmin()
) {

    // start mysql transaction
    $pdo->beginTransaction();

    // set autocommit to true
    $commit = true;

    $id = $_REQUEST['id'];
    $st = $_REQUEST['status'];

    // CHECK ORDER EXISTENCE
    $getOrders = $pdo->prepare("SELECT * FROM customer_buys, customer WHERE customer_buys.uid = customer.id AND customer_buys.id = ?");
    $getOrders->execute([$id]);

    if ($getOrders->rowCount() > 0) {

        // update order and set payment status to 2 (payment confirmed)
        $updateOrder = $pdo->prepare("UPDATE customer_buys SET paid = ? WHERE id = ?");
        $updateOrder = $shop->tryExecute($updateOrder, [$st, $id], $pdo, $commit);

        if ($updateOrder->status) {

            $return->message = "Zahlstatus aktualisiert";
            $return->set = $st;
            $return->status = true;
            exit(json_encode($return));
        } else {

            $return->message = "[1] order update error";
            $return->set = $st;
            exit(json_encode($return));
        }
    } else {
        $return->message = "Dieses Produkt scheint nicht zu existieren";
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
