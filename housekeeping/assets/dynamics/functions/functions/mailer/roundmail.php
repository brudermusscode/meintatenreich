<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "request" => $_REQUEST,
    "mailHeader" => $mail["header"]
];

// objectify return array
$return = (object) $return;

if (isset($_REQUEST['mail']) && $admin->isAdmin()) {

    $text = htmlspecialchars($_REQUEST['mail']);

    if (strlen($text) < 1) {
        $return->message = "Gibt eine Nachricht ein";
        exit(json_encode($return));
    }

    // SELECT: PARAMS, FETCH
    $sel = $pdo->prepare("SELECT mail FROM customer");
    $sel->execute();

    // start mysql transaction
    $pdo->beginTransaction();

    // set counter for later response of how many mails
    // actually had been sent
    $counter = 0;

    // prepare mail's body
    $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/custom.html');
    $mailbody = str_replace('%content%', $text, $mailbody);
    $mailbody = str_replace('\n', '<br>', $mailbody);
    $mailhdr = $mail["header"];

    foreach ($sel->fetchAll() as $to) {

        $to = $to->mail;

        // send mail
        $sendMail = $shop->trySendMail(
            $to,
            "Eine Mitteilung von MeinTatenReich!",
            $mailbody,
            $mailhdr
        );

        if ($sendMail) {

            $counter++;
        }
    }

    // replace white space for proper output
    $newbody = str_replace('\n', '<br>', $text);

    // INSERT MAIL DATA
    $ins = $pdo->prepare("INSERT INTO admin_mails_sent (uref,sid,type,msg) VALUES ('none',?,'all',?)");
    $ins = $shop->tryExecute($ins, [$my->id, $newbody], $pdo, true);

    if ($ins->status) {

        $return->status = true;
        $return->message = "Es wurden <strong>" . $counter . " von "  . $sel->rowCount() . "</strong> Mails verschickt";
        exit(json_encode($return));
    } else {
        $return->message = "Ein Fehler beim eintragen der Rundmail in die Datenbank wurde erkannt";
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
