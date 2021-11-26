<?php


// ERROR CODE :: 0
require_once "../../../../../mysql/_.session.php";


if (isset($_REQUEST['mail']) && $loggedIn && $user['admin'] === '1') {

    $te = $c->real_escape_string(htmlspecialchars($_REQUEST['mail']));

    if (strlen($te) < 1) {
        exit;
    }

    // SELECT: PARAMS, FETCH
    $sel = $c->prepare("SELECT mail FROM customer");
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    $s = $sr->fetch_assoc();

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
        $insMail = $c->prepare("INSERT INTO admin_mails_sent (uref,sid,type,msg,timestamp) VALUES ('none',?,'all',?,?)");
        $insMail->bind_param('sss', $my->id, $newbody, $timestamp);
        $insMail->execute();

        $sendMail = mail($to, $mailsubject, $mailbody, $mailheader);
    }

    if ($sendMail) {
        $c->commit();
        $c->close();
        exit('success');
    } else {
        $c->commit();
        $c->close();
        exit('0');
    }
} else {
    exit;
}
