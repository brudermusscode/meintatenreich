<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// current time
$todayInSeconds = strtotime($main["fulldate"]);

// start mysql transaction
$pdo->beginTransaction();

// update orders with single delivery
$upd = $pdo->prepare("
    UPDATE customer_buys SET cancability = '0'
    WHERE (? - unix_timestamp(timestamp)) >= 7200 
    AND delivery = 'single' 
    AND cancability = '1'
");
$upd = $shop->tryExecute($upd, [$todayInSeconds], $pdo, false);

if ($upd->status) {

    echo "Single: Updated " . $upd->rows . " order(s) <br> <br>";

    // update orders with combi delivery
    // after delivery cost commission
    $upd = $pdo->prepare("
        UPDATE customer_buys SET cancability = '0'
        WHERE (? - unix_timestamp(updated)) >= 21600 
        AND delivery = 'combi' 
        AND price_delivery != '0' 
        AND cancability = '1'
    ");
    $upd = $shop->tryExecute($upd, [$todayInSeconds], $pdo, true);

    if ($upd->status) {

        exit("Combi: Updated " . $upd->rows . " order(s)");
    } else {
        exit("Error updating orders with single delivery");
    }
} else {
    exit("Error updating orders with single delivery");
}
