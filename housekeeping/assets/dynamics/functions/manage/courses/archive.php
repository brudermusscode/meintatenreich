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
    $id = htmlspecialchars($_REQUEST['id']);

    // CHECK IF COURSE EXISTS
    $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $sel->execute([$id]);

    if ($sel->rowCount() > 0) {

        // bstart mysql transaction
        $pdo->beginTransaction();

        // update current course
        $upd = $pdo->prepare("
            UPDATE courses 
            SET deleted = CASE WHEN deleted = '1' THEN '0' ELSE '1' END, 
            updated = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        $upd = $shop->tryExecute($upd, [$id], $pdo, true);

        if ($upd->status) {

            // get archived or unarchived
            $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
            $sel->execute([$id]);

            // fetch course information
            $s = $sel->fetch()->deleted;

            // set output messages for each case
            if ($s == '1') {

                $return->message = "Kurs [" . $id . "] wurde erfolgreich archiviert";
            } else {

                $return->message = "Kurs [" . $id . "] wurde erfolgreich wiederhergestellt";
            }

            $return->status = true;
            $return->set = $s;

            exit(json_encode($return));
        } else {
            $return->message = "Query error. Updating table threw an error";
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
