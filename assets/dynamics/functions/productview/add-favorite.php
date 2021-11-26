<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] == 'add-scard-remember'
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {


    $id = htmlspecialchars($_REQUEST['id']);
    $uid = $my->id;

    // check if product exists
    $getProduct = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $getProduct->execute([$id]);

    if ($getProduct->rowCount() > 0) {

        // fetch product
        $p = $getProduct->fetch();
        $amt = $p->amt;

        // check if product available
        $getProductFavorite = $pdo->prepare("SELECT * FROM shopping_card_remember WHERE pid = ? AND uid = ?");
        $getProductFavorite->execute([$id, $uid]);

        $insert = false;
        $delete = false;

        if ($getProductFavorite->rowCount() < 1) {

            $insert = $pdo->prepare("INSERT INTO shopping_card_remember (uid,pid) VALUES (?,?)");
            $insert->execute([$uid, $id]);

            if ($insert) {
                $pdo->commit();
                exit('1');
            } else {

                $pdo->rollback();
                exit('0');
            }
        } else {

            $delete = $pdo->prepare("DELETE FROM shopping_card_remember WHERE uid = ? AND pid = ?");
            $delete->execute([$uid, $id]);

            if ($delete) {
                $pdo->commit();
                exit('2');
            } else {

                $pdo->rollback();
                exit('0');
            }
        }
    } else {
        exit('0'); // product doesn't exist
    }
} else {
    exit("0");
}
