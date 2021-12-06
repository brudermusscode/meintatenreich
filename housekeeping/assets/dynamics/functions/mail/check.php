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

if ($admin->isAdmin()) {

    // start mysql transaction 
    $pdo->beginTransaction();

    // update mails settings, set check to 1
    $update = $pdo->prepare("UPDATE admin_mails_settings SET mails_checked = '1'");
    $update = $shop->tryExecute($update, [], $pdo, true);

    if ($update->status) {

        $return->status = true;
        exit(json_encode($return));
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
