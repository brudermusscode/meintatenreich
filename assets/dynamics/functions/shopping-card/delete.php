<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!",
    "shoppingCardAmount" => 0
];

// objectify response array
$return = (object) $return;

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'delete-scard'
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {

    // variablize
    $id = $_REQUEST['id'];
    $uid = $my->id;

    // CHECK EXISTENCE
    $getProduct = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ? AND pid = ?");
    $getProduct->execute([$uid, $id]);

    if ($getProduct->rowCount() > 0) {

        // start mysql transactions
        $pdo->beginTransaction();

        // delete shopping card
        $delete = $pdo->prepare("DELETE FROM shopping_card WHERE uid = ? AND pid = ?");
        $delete = $shop->tryExecute($delete, [$uid, $id], $pdo, true);

        if ($delete->status) {

            // create error output
            $return->status = true;
            $return->message = "Vom Warenkorb entfernt";
            $return->shoppingCardAmount = $_SESSION["shoppingCardAmount"]--;

            exit(json_encode($return));
        } else {
            $return->message = "Etwas ist schief gelaufen";
            exit(json_encode($return));
        }
    } else {
        $return->message = "Das Produkt scheint nicht zu exitsieren";
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
