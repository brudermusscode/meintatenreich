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

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (
    isset($_REQUEST['id'], $_REQUEST['date'], $_REQUEST['start'], $_REQUEST['end'])
    && is_numeric($_REQUEST['id'])
    && strlen($_REQUEST['date']) > 0
    && strlen($_REQUEST['start']) > 0
    && strlen($_REQUEST['end'])  > 0
    && $admin->isAdmin()
) {

    $cid = $_REQUEST['id'];
    $date  = htmlspecialchars($_REQUEST['date']);
    $start = htmlspecialchars($_REQUEST['start']);
    $end   = htmlspecialchars($_REQUEST['end']);

    // CHECK IF COURSE EXISTS
    $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ? AND deleted != '1'");
    $sel->execute([$cid]);

    if ($sel->rowCount() > 0) {

        // validate date
        if (validateDate($date)) {

            // validate time
            if (validateDate($start, $format = 'H:i') && validateDate($end, $format = 'H:i')) {

                // start mysql transaction
                $pdo->beginTransaction();

                // insert new appointment
                $ins = $pdo->prepare("INSERT INTO courses_dates (cid, date, start, end) VALUES (?,?,?,?)");
                $ins = $shop->tryExecute($ins, [$cid, $date, $start, $end], $pdo, true);

                if ($ins->status) {

                    $course = $sel->fetch()->name;

                    $return->status = true;
                    $return->message = "Der Termin wurde am <strong>" . $date . "</strong> um <strong>" . $start . "</strong> zum Kurs <strong>" . $course . "</strong> hinzugefügt";

                    exit(json_encode($return));
                } else {
                    $return->message = "Ein Fehler ist beim Eintragen des neuen Termins aufgetreten";
                    exit(json_encode($return));
                }
            } else {
                $return->message = "Die eingegebenen Zeiten haben ein falsches Format. Bitte nutze <strong>STUNDE:MINUTE</strong>";
                exit(json_encode($return));
            }
        } else {
            $return->message = "Das eingegebene Datum hat ein falsches Format. Bitte nutze <strong>JAHR-MONAT-TAG</strong>";
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
