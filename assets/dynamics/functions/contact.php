<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!"
];

// objectify response array
$return = (object) $return;

if (
    isset($_REQUEST['firstname'], $_REQUEST['secondname'], $_REQUEST['ref'], $_REQUEST['cid'], $_REQUEST['msg'])
    && strlen($_REQUEST['firstname']) > 0
    && strlen($_REQUEST['secondname']) > 0
    && strlen($_REQUEST['ref']) > 0
    && strlen($_REQUEST['cid']) > 0
    && strlen($_REQUEST['msg']) > 0
) {


    $fn = $_REQUEST['firstname'];
    $sn = $_REQUEST['secondname'];
    $ref = $_REQUEST['ref'];
    $cid = $_REQUEST['cid'];
    $msg = $_REQUEST['msg'];
    $msg = str_replace('<', '&lt;', $msg);
    $msg = str_replace('<', '&gt;', $msg);
    $msg = str_replace('/', '&frasl;', $msg);
    $msg = str_replace('\\r\\n', '<br>', $msg);
    $msg = str_replace('\\\'', '&prime;', $msg);
    $msg = str_replace('\\"', '&Prime;', $msg);

    // check category
    $getCategory = $pdo->prepare("SELECT * FROM admin_mails_categories WHERE id = ?");
    $getCategory->execute([$cid]);

    if ($getCategory->rowCount() > 0) {

        // fetch category name
        // for use in mail
        $category = $getCategory->fetch()->name;

        // validate firstname
        if ($shop->validateName($fn)) {

            // validate lastname
            if ($shop->validateName($sn)) {

                // validate mail
                if (filter_var($ref, FILTER_VALIDATE_EMAIL)) {

                    // merge full name
                    $fullname = htmlspecialchars($fn . ' ' . $sn);

                    // begin mysql transaction
                    $pdo->beginTransaction();

                    // insert new contact request
                    $insert = $pdo->prepare("INSERT INTO admin_mails_got (fullname, ref, cid, msg) VALUES (?,?,?,?)");
                    $insert = $shop->tryExecute($insert, [$fullname, $ref, $cid, $msg], $pdo, false);

                    if ($insert->status) {

                        // update mails settings in dashboard
                        $update = $pdo->prepare("UPDATE admin_mails_settings SET mails_checked = '0'");
                        $update = $shop->tryExecute($update, [], $pdo, true);

                        if ($update->status) {

                            // prepare mail's body
                            $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/contact.html');
                            $mailbody = str_replace('%category%', $category, $mailbody);

                            // send mail
                            $sendMail = $shop->trySendMail(
                                $my->mail,
                                "Deine Kontaktanfrage auf MeinTatenReich",
                                $mailbody,
                                $mail["header"]
                            );

                            $return->status = true;
                            $return->message = "Deine Nachricht wurde verschickt, wir kümmern uns darum";

                            exit(json_encode($return));
                        } else {
                            exit(json_encode($return));
                        }
                    } else {
                        exit(json_encode($return));
                    }
                } else {
                    $return->message = "Deine E-Mail Adresse enthält ungültige Zeichen. Bitte nutze name@host.endung";
                    exit(json_encode($return));
                }
            } else {
                $return->message = "Dein NAchname enthält ungültige Zeichen";
                exit(json_encode($return));
            }
        } else {
            $return->message = "Dein Vorname enthält ungültige Zeichen";
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    $return->message = "Bitte fülle alle relevanten Felder aus";
    exit(json_encode($return));
}
