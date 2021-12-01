<?php

header('Content-type: application/json');

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] == 'add-scard'
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {


    // variablize
    $id = htmlspecialchars($_REQUEST['id']);
    $uid = $my->id;

    // check product existence
    $getProduct = $pdo->prepare("SELECT * FROM products WHERE id = ? AND available = '1'");
    $getProduct->execute([$id]);

    if ($getProduct->rowCount() > 0) {

        if ($my->verified === '1') {

            // check for reservation
            $getReservation = $pdo->prepare("SELECT * FROM products_reserved WHERE pid = ?");
            $getReservation->execute([$id]);

            if ($getReservation->rowCount() < 1) {

                // check for added already
                $getProductAdded = $pdo->prepare("SELECT * FROM shopping_card WHERE pid = ? AND uid = ?");
                $getProductAdded->execute([$id, $uid]);

                // begin transactions
                $pdo->beginTransaction();

                $insert = false;
                $update = false;
                $insertReservation = false;

                if ($getProductAdded->rowCount() < 1) {

                    // all fine, 
                    // add to shopping card
                    $insert = $pdo->prepare("INSERT INTO shopping_card (uid,pid) VALUES (?,?)");
                    $insert->execute([$uid, $id]);
                } else {

                    // update
                    $update = $pdo->prepare("UPDATE shopping_card SET active = '1', timestamp = CURRENT_TIMESTAMP WHERE uid = ? AND pid = ?");
                    $update->execute([$uid, $id]);
                }

                // reservate product for 6 horas
                $insertReservation = $pdo->prepare("INSERT INTO products_reserved (uid,pid) VALUES (?,?)");
                $insertReservation->execute([$uid, $id]);

                if (($insert || $update) && $insertReservation) {

                    $_SESSION["shoppingCardAmount"]++;

                    $errorInformation = [
                        "status" => true,
                        "shoppingCardAmount" => $_SESSION["shoppingCardAmount"]
                    ];

                    $pdo->commit();
                    exit(json_encode($errorInformation));
                } else {

                    $pdo->rollback();
                    exit('0');
                }
            } else {
                exit('3'); // product in reservation
            }
        } else {
            exit('2'); // user not verified
        }
    } else {
        exit('1'); // product doesn't exist
    }
} else {
    exit("0");
}
