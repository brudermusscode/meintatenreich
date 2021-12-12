<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "request" => $_REQUEST
];

// objectify return array
$return = (object) $return;

if (
    isset($_REQUEST['id'], $_REQUEST['cid'])
    && is_numeric($_REQUEST['id'])
    && is_numeric($_REQUEST['cid'])
    && $admin->isAdmin()
) {

    // CLEAR VARS
    $id = htmlspecialchars($_REQUEST['id']);
    $cid = htmlspecialchars($_REQUEST['cid']);

    // CHECK IF COURSE EXISTS
    $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $sel->execute([$cid]);

    if ($sel->rowCount() > 0) {

        // CHECK IF DATE EXISTS
        $sel = $pdo->prepare("SELECT * FROM courses_dates WHERE id = ? AND cid = ? AND deleted != '1' AND archived != '1'");
        $sel->execute([$id, $cid]);

        if ($sel->rowCount() > 0) {

            // start mysql transaction
            $pdo->beginTransaction();

            // INSERTION
            $upd = $pdo->prepare("UPDATE courses_dates SET deleted = '1', updated = CURRENT_TIMESTAMP WHERE id = ? AND cid = ?");
            $upd = $shop->tryExecute($upd, [$id, $cid], $pdo, true);

            if ($upd->status) {

                $return->status = true;
                $return->message = "Der Termin wurde gelöscht";

                exit(json_encode($return));
            } else {
                $return->message = "Ein Fehler ist beim Löschen des Termins aufgetreten";
                exit(json_encode($return));
            }
        } else {
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
