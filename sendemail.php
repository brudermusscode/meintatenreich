<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$newid = 10;
$key = 200;

// PREPARE VERIFICATION MAIL
$inputmail = "justinleonseidel@gmail.com";
$mailsubject = $mail['subjectSignup'];

$mailbody = file_get_contents('assets/templates/mail/signup.html');
$mailbody = str_replace('%mail%', $inputmail, $mailbody);
$mailbody = str_replace('%url%', $url["main"] . "/verify?id=" . $newid . "&key=" . $key, $mailbody);

$mailheader  = $mail['header'];

if (
    mail($inputmail, $mailsubject, $mailbody, $mailheader)
) {

    echo "mail sent successfully";
} else {

    echo "mail not sent";
}
