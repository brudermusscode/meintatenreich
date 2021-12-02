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

    // begin mysql transaction
    $pdo->beginTransaction();

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
        if ($st == "done" || $st == "canceled") {

            // set autocommiting to false
            $commit = false;
        }

        // variablize
        $s = $getOrder->fetch();
        $to = $s->mail;
        $oi = $s->orderid;

        // update order's status and updated timestamp
        $updateOrder = $pdo->prepare("UPDATE customer_buys SET status = ?, updated = CURRENT_TIMESTAMP WHERE id = ?");
        $updateOrder = $shop->tryExecute($updateOrder, [$st, $id], $pdo, $commit);

        if ($updateOrder->status) {

            // if status was set to canceled or done, we need to care about
            // products inside of the order and their reservations
            if (!$commit) {

                // store product ids in array
                $pids = [];
                $getOrderProducts = $pdo->prepare("SELECT pid FROM customer_buys_products WHERE bid = ?");
                $getOrderProducts->execute([$id]);

                foreach ($getOrderProducts->fetchAll() as $p) {
                    $pids[] = $p->pid;
                }

                foreach ($pids as $pid => $index) {

                    if ($st == "canceled") {
                        if ($pid == array_key_last($pids)) {

                            // set commiting to true, since we need to commit our changes at the
                            // last iteration
                            $commit = true;
                        }
                    }

                    // delete reservations in any of these cases
                    $deleteReservation = $pdo->prepare("DELETE FROM products_reserved WHERE pid = ?");
                    $deleteReservation = $shop->tryExecute($deleteReservation, [$pid], $pdo, $commit);

                    if (!$deleteReservation->status) {
                        $return->message = "[1] reservations update error";
                        exit(json_encode($return));
                    }
                }

                // check if status was set to done in which case we need to
                // update all products of order and set to unavailable
                if ($st == "done") {

                    foreach ($pids as $pid => $index) {

                        if ($pid == array_key_last($pids)) {

                            // set commiting to true, since we need to commit our changes at the
                            // last iteration
                            $commit = true;
                        }

                        // update products and set to unavailable
                        $updateProducts = $pdo->prepare("UPDATE products SET available = '0' WHERE id = ?");
                        $updateProducts = $shop->tryExecute($updateProducts, [$pid], $pdo, $commit);

                        if (!$updateProducts->status) {
                            $return->message = "[3] products update error";
                            exit(json_encode($return));
                        }
                    }
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
