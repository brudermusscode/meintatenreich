<?php

    // ERROR CODE :: 0

    include_once '../../../mysql/_.session.php';

    // GET CURRENT TIMESTAMP IN SECONDS
    $timestampSeconds = strtotime($timestamp);


    // DELETE RESERVATION
    $delIntv = $c->prepare("
        DELETE FROM products_reserved 
        WHERE (? - unix_timestamp(timestamp)) >= 21600 
        AND active = '1' 
        AND isorder = '0'
    ");
    $delIntv->bind_param('s', $timestampSeconds);
    $delIntv->execute();


    // UPDATE SCARD
    $delScard = $c->prepare("
        UPDATE scard SET active = '0'
        WHERE (? - unix_timestamp(timestamp)) >= 21600 
        AND active = '1' 
    ");
    $delScard->bind_param('s', $timestampSeconds);
    $delScard->execute();


    if($delIntv && $delScard) {
        $c->commit();
        $delIntv->close();
        $delScard->close();
        $c->close();
        exit('ake');
    } else {
        $c->rollback();
        $delIntv->close();
        $delScard->close();
        $c->close();
        exit('oops');
    }

?>