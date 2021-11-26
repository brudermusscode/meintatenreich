<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

header('Content-type: application/json');

$cArray = new checkArray;

if (
    isset($_REQUEST['action'], $_REQUEST['delivery'])
    && $_REQUEST['action'] === 'pricing'
    && $_REQUEST['delivery'] !== ''
    && $loggedIn
) {

    $uid = $my->id;
    $delivery = htmlspecialchars($_REQUEST['delivery']);

    // get products from shopping card
    $getProducts = $pdo->prepare("SELECT pid FROM shopping_card WHERE uid = ? AND active = '1'");
    $getProducts->execute([$uid]);

    if ($getProducts->rowCount() > 0) {

        // create array of all products
        $products = [];
        foreach ($getProducts->fetchAll() as $p) {

            $products[] = (int)$p->pid;
        }

        // check if all product ID's are ints
        if ($cArray->all($products, 'is_int')) {

            $validDels = ['single', 'combi'];

            // check for valid delivery method
            if (in_array($delivery, $validDels)) {

                $price = [];

                // + get product prices
                // + add to array
                foreach ($products as $pid) {

                    $getProductsPrices = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                    $getProductsPrices->execute([$pid]);

                    if ($getProductsPrices->rowCount() > 0) {
                        $pp = $getProductsPrices->fetch();
                        $price[] = (float)$pp->price;
                    }
                }

                // sum up all prices from array
                $price = array_sum($price);


                if ($delivery === 'single') {
                    $delivery = count($products);
                }

                $price = $price;

                $res = [
                    'price' => $price,
                    'delAmt' => $delivery
                ];

                exit(json_encode($res));
            } else {
                exit('3'); // delivery method is invalid
            }
        } else {
            exit('2'); // products are invalid
        }
    } else {
        exit('1'); // shopping card is empty
    }
} else {
    exit("0");
}
