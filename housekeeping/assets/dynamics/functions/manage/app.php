<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Das ist wohl ein Oopsie passiert"
];

// objectify return array
$return = (object) $return;

if (
    isset($_REQUEST['maintenance'], $_REQUEST['displayerrors'])
    && $admin->isAdmin()
) {

    // variablize
    $de = $_REQUEST['displayerrors'];
    $mn = $_REQUEST['maintenance'];

    // start mysql transaction
    $pdo->beginTransaction();

    // UPDATE
    $update = $pdo->prepare("UPDATE system_settings SET maintenance = ?, display_errors = ? WHERE id = ?");
    $update = $shop->tryExecute($update, [$mn, $de, $conf["environment"]], $pdo, true);

    if ($update->status) {

        $return->message = "Einstellungen gespeichert";
        exit(json_encode($return));
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
