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

    // check if order exists
    $getOrder = $pdo->prepare("SELECT * FROM customer_buys WHERE id = ? AND uid = ?");
    $getOrder->execute([$id, $uid]);

    if ($getOrder->rowCount()) {

        // add debug
        $debugArray["orderExists"] = true;

        // UPDATE ORDER
        $update = $pdo->prepare("UPDATE customer_buys SET paid = '1', updated = CURRENT_TIMESTAMP WHERE id = ? AND uid = ?");
        $update->execute([$id, $uid]);

        if ($update) {

            $pdo->commit();
            exit(json_encode($debugArray));
        } else {

            $pdo->rollback();
            exit('0');
        }
    } else {
        exit('1');
    }
} else {
    exit("0");
}
