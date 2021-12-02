<?php


// ERROR CODE :: 0
require_once "../../../../../mysql/_.session.php";


if (isset($_REQUEST['mail']) && $admin->isAdmin()) {

    $te = $pdo->real_escape_string(htmlspecialchars($_REQUEST['mail']));

    if (strlen($te) < 1) {
        exit;
    }

    // SELECT: PARAMS, FETCH
    $sel = $pdo->prepare("SELECT mail FROM customer");
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    $s = $sr->fetch();

    foreach ($s as $mail) {

        $to = $mail;

        // PREPARE MAIL
        $mailbody = file_get_contents('../../../../../assets/templates/mail/custom.html');
        $mailbody = str_replace('%content%', $te, $mailbody);
        $mailbody = str_replace('\n', '<br>', $mailbody);
        $mailsubject = 'Eine Mitteilung von MeinTatenreich!';
        $mailheader  = $config['mail_header'];

        $newbody = str_replace('\n', '<br>', $te);

        // INSERT MAIL DATA
        $insMail = $pdo->prepare("INSERT INTO admin_mails_sent (uref,sid,type,msg,timestamp) VALUES ('none',?,'all',?,?)");
        $insMail->bind_param('sss', $my->id, $newbody, $timestamp);
        $insMail->execute();

        $sendMail = mail($to, $mailsubject, $mailbody, $mailheader);
    }

    if ($sendMail) {
        $pdo->commit();
        $pdo->close();
        exit('success');
    } else {
        $pdo->commit();
        $pdo->close();
        exit('0');
    }
} else {
    exit;
}
