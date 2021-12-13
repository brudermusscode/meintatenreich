<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "set" => NULL,
    "message" => "Da ist wohl ein Oopsie passiert",
    "request" => $_REQUEST
];

// objectify return array
$return = (object) $return;



if (
    isset($_REQUEST['deliverycosts'], $_REQUEST['id']) &&
    $admin->isAdmin()
) {

    // replace , with . for delivery costs
    $deliverycosts = preg_replace('/[,]/', '.', $_REQUEST['deliverycosts']);

    // and check if its float
    if (is_numeric($deliverycosts)) {

        // numberformat the price for database insertion
        $dc = number_format($deliverycosts, 2, ',', '.');

        // variablize
        $id = $_REQUEST['id'];

        // CHECK ORDER EXISTENCE
        $sel = $pdo->prepare("
            SELECT * 
            FROM customer_buys, customer
            WHERE customer_buys.uid = customer.id
            AND customer_buys.id = ?
            AND customer_buys.delivery = 'combi'
        ");
        $sel->execute([$id]);

        if ($sel->rowCount() > 0) {

            // start mysql tra´nsaction
            $pdo->beginTransaction();

            // fetch customer information
            $s = $sel->fetch();

            $to = $s->mail;
            $oi = $s->orderid;

            // PREPARE VERIFICATION MAIL
            $mailTopic = "Versandkosten zu deiner Bestellung - MeinTatenReich";
            $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/deliverycosts.html');
            $mailbody = str_replace('%mail%', $to, $mailbody);
            $mailbody = str_replace('%orderid%', $oi, $mailbody);
            $mailbody = str_replace('%delcosts%', $dc, $mailbody);

            // send mail
            $sendMail = $shop->trySendMail(
                $s->mail,
                $mailTopic,
                $mailbody,
                $mail["header"]
            );

            if ($sendMail) {

                // SET DELIVERY COSTS
                $upd = $pdo->prepare("UPDATE customer_buys SET price_delivery = ? WHERE id = ?");
                $upd = $shop->tryExecute($upd, [$deliverycosts, $id], $pdo, true);

                if ($upd->status) {

                    $return->status = true;
                    $return->message = "Die Versandkosten wurden dem Kunden erfolgreich mitgeteilt. Der Kunde ehält nun 6 Stunden Zeit, um seine Bestellung wieder zu stornieren";

                    exit(json_encode($return));
                } else {
                    $return->message = "Beim Eintragen der Versandkosten in die Datenbank ein Fehler aufgetreten. Der Kunde wurde über die Versandkosten informiert und erhält nun 6 Stunden Zeit, um seine Bestellung zu stornieren";
                    exit(json_encode($return));
                }
            } else {
                $return->message = "Beim Versenden der Mail ist ein Fehler aufgetreten. Der Kunde wurde noch nicht benachrichtigt";
                exit(json_encode($return));
            }
        } else {
            exit(json_encode($return));
        }
    } else {
        $return->message = "Bitte gib einen Preis ein";
        exit(json_encode($return));
    }
} else {
    $return->message = "Bitte gib einen Preis ein";
    exit(json_encode($return));
}
