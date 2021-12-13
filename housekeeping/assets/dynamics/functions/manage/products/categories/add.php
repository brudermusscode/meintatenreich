<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "id" => 0
];

// objectify return array
$return = (object) $return;

if (isset($_REQUEST['name']) && strlen($_REQUEST['name']) > 0 && $admin->isAdmin()) {

    $pdo->beginTransaction();

    $name = htmlspecialchars($_REQUEST['name']);

    $sel = $pdo->prepare("SELECT * FROM products_categories WHERE category_name = ?");
    $sel->execute([$name]);

    if ($sel->rowCount() < 1) {

        // INSERT CATEGORY
        $stmt = $pdo->prepare("INSERT INTO products_categories (category_name) VALUES (?)");
        $stmt = $shop->tryExecute($stmt, [$name], $pdo, true);

        if ($stmt->status) {

            $id = $stmt->lastInsertId;

            $return->status = true;
            $return->message = "Kategorie hinzugefügt: <strong>" . $name . "</strong>";
            $return->id = $id;
            exit(json_encode($return));
        } else {
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {

    $return->message = "Bitte alle Felder ausfüllen";
    exit(json_encode($return));
}
