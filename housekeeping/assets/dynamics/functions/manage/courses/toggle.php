<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "set" => 0,
    "message" => "Da ist wohl ein Oopsie passiert",
    "REQUEST" => $_REQUEST
];

// objectify return array
$return = (object) $return;

if (
    isset($_REQUEST['id'])
    && is_numeric($_REQUEST['id'])
    && $admin->isAdmin()
) {

    // CLEAR VARS
    $id = $_REQUEST['id'];

    // CHECK IF COURSE EXISTS
    $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $sel->execute([$id]);

    if ($sel->rowCount() > 0) {

        // start mysql transaction
        $pdo->beginTransaction();

        // INSERTION
        $upd = $pdo->prepare("UPDATE courses SET active = CASE WHEN active = '0' THEN '1' ELSE '0' END, updated = CURRENT_TIMESTAMP WHERE id = ?");
        $upd = $shop->tryExecute($upd, [$id], $pdo, true);

        if ($upd->status) {

            // get set status
            $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
            $sel->execute([$id]);

            // fetch information
            $set = $sel->fetch()->active;

            // set messages for activated
            if ($set == 0) {
                $return->set = "0";
                $return->message = "Kurs [" . $id . "] deaktiviert";

                // .. or deactivated
            } else {
                $return->set = "1";
                $return->message = "Kurs [" . $id . "] aktiviert";
            }

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
