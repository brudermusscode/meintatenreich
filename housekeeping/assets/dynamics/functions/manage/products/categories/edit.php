<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert"
];

// objectify return array
$return = (object) $return;

if (
    isset($_REQUEST['id'], $_REQUEST['name'])
    && is_numeric($_REQUEST['id'])
    && strlen($_REQUEST['name']) > 0
    && $admin->isAdmin()
) {

    // start m,ysql transaction
    $pdo->beginTransaction();

    $id = $_REQUEST['id'];
    $na = htmlspecialchars($_REQUEST['name']);

    // CHECK CATEGORY EXISTENCE
    $sel = $pdo->prepare("SELECT * FROM products_categories WHERE id = ? AND id != '0'");
    $sel->execute([$id]);

    if ($sel->rowCount() > 0) {

        // UPDATE PRODUCT CATEGORY
        $upd = $pdo->prepare("UPDATE products_categories SET category_name = ? WHERE id = ?");
        $upd = $shop->tryExecute($upd, [$na, $id], $pdo, true);

        if ($upd->status) {

            $return->status = true;
            $return->message = "Kategorie bearbeitet";
            exit(json_encode($return));
        } else {
            $return->message = "update error";
            exit(json_encode($return));
        }
    } else {
        $return->message = "select error";
        exit(json_encode($return));
    }
} else {
    $return->message = "authority error";
    exit(json_encode($return));
}
