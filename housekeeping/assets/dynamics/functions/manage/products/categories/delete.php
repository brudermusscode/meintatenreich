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

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && $admin->isAdmin()) {

    // start mysql transaction
    $pdo->beginTransaction();

    $id = $_REQUEST['id'];

    $sel = $pdo->prepare("SELECT * FROM products_categories WHERE id = ? AND id != '0'");
    $sel->execute([$id]);

    if ($sel->rowCount() > 0) {

        // delete category
        $del = $pdo->prepare("DELETE FROM products_categories WHERE id = ?");
        $del = $shop->tryExecute($del, [$id], $pdo, false);

        if ($del->status) {

            if ($del->rows > 0) {

                // update product's categories and set to 0 (no category)
                $upd = $pdo->prepare("UPDATE products SET cid = '0' WHERE cid = ?");
                $upd = $shop->tryExecute($upd, [$id], $pdo, false);

                if (!$upd->status) {
                    exit(json_encode($return));
                }
            }

            $return->status = true;
            $return->message = "Gelöscht";

            $pdo->commit();

            exit(json_encode($return));
        } else {
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
