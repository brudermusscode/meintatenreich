<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "REQUEST" => $_REQUEST
];

// objectify return array
$return = (object) $return;

if (isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"]) && $admin->isAdmin()) {

    $id = $_REQUEST["id"];

    // check if sent category id is existent
    $getProducts = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $getProducts->execute([$id]);

    if ($getProducts->rowCount() > 0) {

        $update = $pdo->prepare("UPDATE products SET available = '0' WHERE id = ?");
        $update = $shop->tryExecute($update, [$id], $pdo, true);

        if ($update->status) {

            $return->status = true;
            $return->message = "Produkt [" . $id . "] deaktiviert";
            exit(json_encode($return));
        } else {
            $return->message = $update->message;
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
