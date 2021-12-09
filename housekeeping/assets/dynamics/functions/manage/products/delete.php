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

        // start mysql transaction
        $pdo->beginTransaction();

        $update = $pdo->prepare("UPDATE products SET products.deleted = CASE WHEN products.deleted = '1' THEN '0' ELSE '1' END WHERE products.id = ?");
        $update = $shop->tryExecute($update, [$id], $pdo, true);

        if ($update->status) {

            // check if was archived or not
            $select = $pdo->prepare("SELECT deleted FROM products WHERE products.id = ?");
            $select->execute([$id]);

            $archived = $select->fetch()->deleted;
            if ($archived == "0") {
                $archived = "wiederhergestellt";
            } else {
                $archived = "archiviert";
            }

            $return->status = true;
            $return->message = "Produkt [" . $id . "] " . $archived;
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
