<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'delete-scard'
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {

    $id = htmlspecialchars($_REQUEST['id']);
    $uid = htmlspecialchars($my->id);

    // CHECK EXISTENCE
    $getProduct = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ? AND pid = ? AND active = '1'");
    $getProduct->execute([$uid, $id]);
    if ($getProduct->rowCount() > 0) {

        // UPDATE SCARD
        $update = $pdo->prepare("UPDATE shopping_card SET active = '0' WHERE uid = ? AND pid = ?");
        $update->execute([$uid, $id]);

        // DELETE RESERVATION
        $delete = $pdo->prepare("DELETE FROM products_reserved WHERE uid = ? AND pid = ?");
        $delete->execute([$uid, $id]);

        if ($update && $delete) {

            $pdo->commit();
            exit('2');
        } else {

            $pdo->rollback();
            exit('0');
        }
    } else {
        exit('1'); // product doesn't exist
    }
} else {
    exit("0");
}
