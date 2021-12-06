<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "toggle" => 0,
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

        if ($getProducts->fetch()->available == '0') {
            $set = '1';
            $setMessage = "aktiviert";
            $return->toggle = 1;
        } else {
            $set = '0';
            $setMessage = "deaktiviert";
            $return->toggle = 0;
        }

        // start mysql transaction
        $pdo->beginTransaction();

        $update = $pdo->prepare("UPDATE products SET available = ? WHERE id = ?");
        $update = $shop->tryExecute($update, [$set, $id], $pdo, true);

        if ($update->status) {

            $return->status = true;
            $return->message = "Produkt [" . $id . "] " . $setMessage;
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
