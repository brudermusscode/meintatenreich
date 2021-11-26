<?php


// ERROR CODE :: 0
require_once "../../../../../mysql/_.session.php";


if (isset($_REQUEST['id'], $_REQUEST['text']) && $loggedIn && $user['admin'] === '1') {

    $id = $_REQUEST['id'];
    $te = $c->real_escape_string(htmlspecialchars($_REQUEST['text']));

    if (is_numeric($id)) {

        // CHECK IF CUSTOMER EXISTS
        $sel = $c->prepare("
                SELECT *
                FROM customer
                WHERE id = ?
            ");
        $sel->bind_param('s', $id);
        $sel->execute();
        $sel_r = $sel->get_result();

        if ($sel_r->rowCount() > 0) {

            // FETCH CUSTOMER
            $s = $sel_r->fetch_assoc();
            $sel->close();

            // GET NEEDED DATA
            $to = $s['mail'];
        } else {
            exit('1');
        }
    } else {

        if (filter_var($id, FILTER_VALIDATE_EMAIL)) {

            $to = $id;
        } else {
            exit('1');
        }
    }

    // PREPARE MAIL
    $mailbody = file_get_contents('../../../../../assets/templates/mail/custom.html');
    $mailbody = str_replace('%content%', $te, $mailbody);
    $mailbody = str_replace('\n', '<br>', $mailbody);
    $mailsubject = 'Eine Mitteilung von MeinTatenreich!';
    $mailheader  = $config['mail_header'];

    $newbody = str_replace('\n', '<br>', $te);

    // INSERT MAIL DATA
    $insMail = $c->prepare("INSERT INTO admin_mails_sent (uref,sid,msg,timestamp) VALUES (?,?,?,?)");
    $insMail->bind_param('ssss', $id, $my->id, $newbody, $timestamp);
    $insMail->execute();


    if ($insMail && mail($to, $mailsubject, $mailbody, $mailheader)) {

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
