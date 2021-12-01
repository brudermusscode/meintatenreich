<?php

header('Content-type: application/json');

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'delete-scard'
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {

    $debugArray = [];

    // variablize
    $id = $_REQUEST['id'];
    $uid = $my->id;

    // CHECK EXISTENCE
    $getProduct = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ? AND pid = ? AND active = '1'");
    $getProduct->execute([$uid, $id]);

    if ($getProduct->rowCount() > 0) {

        $debugArray["productExists"] = true;

        // start mysql transactions
        $pdo->beginTransaction();

        // update shopping card
        $update = $pdo->prepare("UPDATE shopping_card SET active = '0' WHERE uid = ? AND pid = ?");
        $try = $shop->tryExecute($update, [$uid, $id], $pdo);

        if (is_array($try) && $try) {

            $debugArray["updatedShoppingCard"] = true;

            // delete reservation
            $delete = $pdo->prepare("DELETE FROM products_reserved WHERE uid = ? AND pid = ?");
            $try = $shop->tryExecute($delete, [$uid, $id], $pdo);

            if (is_array($try) && $try) {

                $debugArray["deletedReservations"] = true;

                // update shopping card amount
                $shoppingCardAmount = $_SESSION["shoppingCardAmount"] - 1;
                $_SESSION["shoppingCardAmount"]--;

                // create error output
                $response = [
                    "status" => true,
                    "shoppingCardAmount" => $shoppingCardAmount
                ];

                // commit and exit
                $pdo->commit();
                exit(json_encode($response));
            }
        } else {
            exit("0");
        }
    } else {
        exit("1"); // product doesn't exist
    }
} else {
    exit("0");
}
