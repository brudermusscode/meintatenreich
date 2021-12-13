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
    isset($_REQUEST["mail"], $_REQUEST['text']) &&
    !empty($_REQUEST['text']) &&
    !empty($_REQUEST["mail"]) &&
    $admin->isAdmin()
) {

    // start mysql transaction
    $pdo->beginTransaction();

    $text = htmlspecialchars($_REQUEST['text']);
    $inputmail = $_REQUEST["mail"];

    // check if customer with this email address exists
    $sel = $pdo->prepare("SELECT * FROM customer WHERE mail = ?");
    $sel->execute([$inputmail]);

    if ($sel->rowCount() > 0) {

        if (filter_var($inputmail, FILTER_VALIDATE_EMAIL)) {

            // prepare mail's body
            $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/custom.html');
            $mailbody = str_replace('%content%', $text, $mailbody);
            $mailbody = str_replace('\n', '<br>', $mailbody);

            // send mail
            $sendMail = $shop->trySendMail(
                $inputmail,
                "Eine Mitteilung von MeinTatenReich!",
                $mailbody,
                $mail["header"]
            );

            if ($sendMail) {

                // replace white space for proper output
                $newbody = str_replace('\n', '<br>', $text);

                // insert sent mail into database
                $ins = $pdo->prepare("INSERT INTO admin_mails_sent (uref,sid,type,msg) VALUES (?,'1','single',?)");
                $ins = $shop->tryExecute($ins, [$inputmail, $newbody], $pdo, true);

                if ($ins->status) {

                    $return->status = true;
                    $return->message = "Die Mail wurde erfolgreich an <strong>" . $inputmail . "</strong> verschickt und kann nun im Nachrichten-Center eingesehen werden";
                    exit(json_encode($return));
                } else {
                    $return->message = "Ein Fehler beim eintragen der Mail in die Datenbank wurde erkannt";
                    exit(json_encode($return));
                }
            } else {
                $return->message = "Die Mail konnte nicht versendet werden. Ein Fehler ist aufgetreten";
                exit(json_encode($return));
            }
        } else {
            $return->message = "Die E-Mail Adresse des Kunden ist ungültig";
            exit(json_encode($return));
        }
    } else {
        $return->message = "Der Kunde scheint nicht (mehr) zu existieren";
        exit(json_encode($return));
    }
} else {
    $return->message = "Bitte gib eine Nachricht ein";
    exit(json_encode($return));
}
