<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!",
    "shoppingCardAmount" => NULL
];

// objectify response array
$return = (object) $return;

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

                // add to shopping card
                $addShoppingCard = $pdo->prepare("INSERT INTO shopping_card (uid,pid) VALUES (?,?)");
                $addShoppingCard = $shop->tryExecute($addShoppingCard, [$uid, $id], $pdo, false);

                if ($addShoppingCard->status) {

                    // reservate product for 6 horas
                    $insertReservation = $pdo->prepare("INSERT INTO products_reserved (uid,pid) VALUES (?,?)");
                    $insertReservation = $shop->tryExecute($insertReservation, [$uid, $id], $pdo, true);

                    if ($insertReservation->status) {

                        // store error information
                        $return->status = true;
                        $return->message = "Produkt wurde hinzugefügt!";
                        $return->shoppingCardAmount = $_SESSION["shoppingCardAmount"]++;

                        exit(json_encode($return));
                    } else {
                        exit(json_encode($insertReservation));
                    }
                } else {
                    exit(json_encode($insertReservation));
                }
            } else {
                $return->message = "Dieses Produkt ist bereits reserviert";
                exit(json_encode($return)); // product in reservation
            }
        } else {
            $return->message = "Bitte verifiziere deinen Account";
            exit(json_encode($return)); // user not verified
        }
    } else {
        $return->message = "Das Produkt scheint nicht mehr zu existieren";
        exit(json_encode($return)); // product doesn't exist
    }
} else {
    exit(json_encode($return));
}
