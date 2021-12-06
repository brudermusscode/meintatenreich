<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

header('Content-type: application/json');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!",
    "price" => 0,
    "delAmt" => 0
];

// objectify response array
$return = (object) $return;

if (
    isset($_REQUEST['action'], $_REQUEST['delivery'])
    && $_REQUEST['action'] === 'pricing'
    && $_REQUEST['delivery'] !== ''
    && $loggedIn
) {

    $uid = $my->id;
    $delivery = htmlspecialchars($_REQUEST['delivery']);

    // get products from shopping card
    $getShoppingCard = $pdo->prepare("
        SELECT shopping_card.pid, products.price
        FROM shopping_card, products 
        WHERE products.id = shopping_card.pid 
        AND shopping_card.uid = ?
    ");
    $getShoppingCard->execute([$uid]);

    if ($getShoppingCard->rowCount() > 0) {

        $products = [];
        $prices = [];

        // create array of all products & prices of products
        foreach ($getShoppingCard->fetchAll() as $p) {

            $products[] = (int) $p->pid;
            $price[] = (float) $p->price;
        }

        // store all valid delivery methods
        $validDels = ['single', 'combi'];

        // check for valid delivery method
        if (in_array($delivery, $validDels)) {

            // sum up all prices from array
            $price = array_sum($price);

            if ($delivery == 'single') {
                $delivery = count($products);
            }

            $return->status = true;
            $return->message = "";
            $return->price = $price;
            $return->delivery = $delivery;

            exit(json_encode($return));
        } else {
            exit('3'); // delivery method is invalid
        }
    } else {
        exit('1'); // shopping card is empty
    }
} else {
    exit("0");
}
