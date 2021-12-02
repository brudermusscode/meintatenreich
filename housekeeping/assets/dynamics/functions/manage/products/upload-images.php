<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/housekeeping/dynamics/libs/bulletproof/upload.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "fileName" => NULL
];

// objectify return array
$return = (object) $return;

$image = new Bulletproof\Image($_FILES);

if ($image['pictures']) {

    $strn = $login->createString(24);

    $image->setLocation($sroot . $uploaddir);

    $imgname = $image->setName('prod-' . $strn);
    $mime = $image->getMime();
    $fullname = trim('prod-' . $strn . '.' . $mime);

    $upload = $image->upload();

    if ($upload) {

        $return->message = "Upload erfolgreich";
        exit(json_encode($return));
    } else {

        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
