<?php

    // ERROR CODE :: 0

    include_once '../../../mysql/_.session.php';

    // GET CURRENT TIMESTAMP IN SECONDS
    $timestampSeconds = strtotime($timestamp);


    // UPDATE ORDERS
    $updOrders = $c->prepare("
        UPDATE customer_buys SET cancability = '0'
        WHERE (? - unix_timestamp(timestamp)) >= 7200 
        AND delivery = 'single' 
        AND cancability = '1'
    ");
    $updOrders->bind_param('s', $timestampSeconds);
    $updOrders->execute();


    // UPDATE ORDERS
    $updOrdersPaid = $c->prepare("
        UPDATE customer_buys SET cancability = '0'
        WHERE delivery = 'combi' 
        AND price_delivery > '0' 
        AND (? - unix_timestamp(updated)) >= 21600 
        AND cancability = '1'
    ");
    $updOrdersPaid->bind_param('s', $timestampSeconds);
    $updOrdersPaid->execute();


    if($updOrders && $updOrdersPaid) {
        $c->commit();
        $updOrders->close();
        $c->close();
        exit('ake');
    } else {
        $c->rollback();
        $updOrders->close();
        $c->close();
        exit('oops');
    }

?>