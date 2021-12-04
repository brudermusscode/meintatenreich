<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/housekeeping/assets/dynamics/libs/bulletproof/upload.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "fileName" => NULL,
    "fileId" => NULL
];

// objectify return array
$return = (object) $return;

// open new image upload object
$image = new Bulletproof\Image($_FILES);

// create array for valid mime types
$validMimes = ["png", "jpg", "jpeg", "gif", "svg"];

if ($image['pictures']) {

    $size = $image->getSize();

    // check if size is lower than 3 MB
    if ($size <= 3145728) {

        $strn = $login->createString(24);
        $image->setLocation($sroot . "/" . $url["upload"]);
        $imgname = $image->setName('prod-' . $strn);
        $image->setMime($validMimes);
        $mime = $image->getMime();
        $fullname = trim('prod-' . $strn . '.' . $mime);
        $upload = $image->upload();

        // try upload
        if ($upload) {

            // start mysql transaction
            $pdo->beginTransaction();

            // insert product into database
            // product id will be assigned when product actually
            // gets added
            $insert = $pdo->prepare("INSERT INTO products_images (url) VALUES (?)");
            $insert = $shop->tryExecute($insert, [$fullname], $pdo, true);

            if ($insert->status) {

                // reassign return values with id, fullname and status for return script
                $return->status = true;
                $return->fileName = $fullname;
                $return->fileId = $insert->lastInsertId;
                $return->message = "<strong>" . $return->fileName . "</strong> hochgeladen";
                exit(json_encode($return));
            } else {
                $return->message = "[" . $insert->code . "]" . $insert->message;
                exit(json_encode($return));
            }
        } else {
            $return->message = $image->getError();
            exit(json_encode($return));
        }
    } else {
        $return->message = "Maximale Größe für Bilder sind 3 MB";
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
