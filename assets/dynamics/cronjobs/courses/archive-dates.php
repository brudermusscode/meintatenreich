<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$today = $main["fulldate"];

// start mysql transaction
$pdo->beginTransaction();

// update all course dates and set to archived
// if time is in the past
$upd = $pdo->prepare("UPDATE courses_dates SET archived = '1' WHERE CONCAT(courses_dates.date, ' ', courses_dates.end) < ?");
$upd = $shop->tryExecute($upd, [$today], $pdo, true);
if ($upd->status) {

    exit("Updated " . $upd->rows . " date(s)");
} else {

    exit("Error updating dates");
}
