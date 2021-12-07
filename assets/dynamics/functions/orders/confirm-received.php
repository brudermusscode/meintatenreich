<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!"
];

// objectify response array
$return = (object) $return;

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {

    // variablize
    $id = $_REQUEST['id'];
    $uid = $my->id;

    // check order existence and source
    $getOrder = $pdo->prepare("SELECT * FROM customer_buys WHERE uid = ? AND id = ?");
    $getOrder->execute([$uid, $id]);

    if ($getOrder->rowCount() > 0) {

        // fetch order information
        $o = $getOrder->fetch();

        // start mysql transaction
        $pdo->beginTransaction();

        // UPDATE ORDER
        $update = $pdo->prepare("UPDATE customer_buys SET status = 'done', updated = CURRENT_TIMESTAMP WHERE id = ? AND uid = ?");
        $update = $shop->tryExecute($update, [$id, $uid], $pdo, true);

        if ($update->status) {

            $mailUrl = '/assets/templates/mail/dashbrd/orderStatusDone.html';
            $mailTopic = "Deine Bestellung auf MeinTatenReich ist nun abgeschlossen!";
            $mailbody = file_get_contents($url["main"] . $mailUrl);
            $mailbody = str_replace('%orderid%', $o->orderid, $mailbody);

            // send mail
            $sendMail = $shop->trySendMail(
                $my->mail,
                $mailTopic,
                $mailbody,
                $mail["header"]
            );

            $return->message = "Vielen Dank für die Bestätigung!";
            $return->status = true;

            exit(json_encode($return));
        } else {
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
