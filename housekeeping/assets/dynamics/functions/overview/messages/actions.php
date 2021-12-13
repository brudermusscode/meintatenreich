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
    isset($_REQUEST['isread'], $_REQUEST['fav'], $_REQUEST['id'])
    && $admin->isAdmin()
) {

    $isread = $_REQUEST['isread'];
    $fav = $_REQUEST['fav'];
    $id = $_REQUEST['id'];

    if ($isread !== '1' && $isread !== '0') {
        $isread = '1';
    }

    if ($fav !== '1' && $fav !== '0') {
        $fav = '1';
    }

    // SELECT: PARAMS, NO FETCH, CHECK nUM ROWS
    $sel = $pdo->prepare("SELECT * FROM admin_mails_got WHERE id = ?");
    $sel->execute([$id]);

    if ($sel->rowCount() > 0) {

        // start mysql transactions
        $pdo->beginTransaction();

        // UPDATE
        $upd = $pdo->prepare("UPDATE admin_mails_got SET isread = ?, fav = ? WHERE id = ?");
        $upd = $shop->tryExecute($upd, [$isread, $fav, $id], $pdo, true);

        if ($upd->status) {

            $return->status = true;
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
