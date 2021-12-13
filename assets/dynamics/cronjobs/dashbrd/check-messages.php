<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$return = [
    "status" => false,
    "playSound" => true
];

$return = (object) $return;

// start mysql transaction
$pdo->beginTransaction();

$stmt = $pdo->prepare("SELECT * FROM admin_mails_settings WHERE mails_checked = '0' AND id = '1'");
$stmt->execute();

if ($stmt->rowCount() > 0) {

    $return->status = true;

    exit(json_encode($return));
} else {
    exit(json_encode($return));
}
