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
    $id = $_REQUEST['id'];
    $uid = $my->id;

    // check product existence
    $getProduct = $pdo->prepare("SELECT * FROM products WHERE id = ? AND available = '1'");
    $getProduct->execute([$id]);

    if ($getProduct->rowCount() > 0) {

        // check verification status of the customer
        if ($my->verified === '1') {

            // check if the product was added already
            $getProductAdded = $pdo->prepare("SELECT * FROM shopping_card WHERE pid = ? AND uid = ? LIMIT 1");
            $getProductAdded->execute([$id, $uid]);

            if ($getProductAdded->rowCount() < 1) {

                // begin transactions
                $pdo->beginTransaction();

                // add to shopping card
                // commit changes on this query
                $addShoppingCard = $pdo->prepare("INSERT INTO shopping_card (uid, pid) VALUES (?, ?)");
                $addShoppingCard = $shop->tryExecute($addShoppingCard, [$uid, $id], $pdo, true);

                if ($addShoppingCard->status) {

                    // store error information
                    $return->status = true;
                    $return->message = "Produkt wurde hinzugefügt!";
                    $return->shoppingCardAmount = $_SESSION["shoppingCardAmount"]++;

                    exit(json_encode($return));
                } else {
                    exit(json_encode($insertReservation));
                }
            } else {
                $return->message = "Das Produkt befindet sich bereits in deinem Warenkorb";
                exit(json_encode($return)); // user not verified
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
    $return->message = "Bitte logge dich ein, bevor du Produkte zum Warenkorb hinzufügst";
    exit(json_encode($return));
}
