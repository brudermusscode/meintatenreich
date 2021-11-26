<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$pdo->beginTransaction();

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

        if (preg_match('/^[A-Za-z\- äöüÄÖÜß]+$/', $fn)) {

            if (preg_match('/^[A-Za-z\- äöüÄÖÜß]+$/', $sn)) {

                if (filter_var($ref, FILTER_VALIDATE_EMAIL)) {

                    $fullname = htmlspecialchars($fn . ' ' . $sn);

                    // insert new contact request
                    $insertContactRequest = $pdo->prepare("INSERT INTO admin_mails_got (fullname, ref, cid, msg) VALUES (?,?,?,?)");
                    $insertContactRequest->execute([$fullname, $ref, $cid, $msg]);

                    // update dashboard: messages
                    $updateMessages = $pdo->prepare("UPDATE admin_mails_settings SET mails_checked = '0' WHERE id = '1'");
                    $updateMessages->execute();

                    // PREPARE MAIL
                    /* $mailbody = file_get_contents('../../../../assets/templates/mail/contact.html');
                    $mailbody = str_replace('%msg%', $msg, $mailbody);
                    $mailsubject = 'Wir haben Deine Nachricht erhalten!';
                    $mailheader  = $config['mail_header']; */

                    if (
                        $insertContactRequest && $updateMessages
                        // mail($ref, $mailsubject, $mailbody, $mailheader)
                    ) {

                        $pdo->commit();
                        exit('success');
                    } else {

                        $pdo->rollback();
                        exit('0');
                    }
                } else {
                    exit('4'); // mail invalid
                }
            } else {
                exit('3'); // secondname invalid
            }
        } else {
            exit('2'); // firstname invalid
        }
    } else {
        exit('1'); // wrong category
    }
} else {
    exit("0");
}
