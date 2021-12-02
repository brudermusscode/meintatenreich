<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['status'], $_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $id = $_REQUEST['id'];
    $st = $_REQUEST['status'];

    // CHECK ORDER EXISTENCE
    $sel = $pdo->prepare("
            SELECT * 
            FROM customer_buys, customer
            WHERE customer_buys.uid = customer.id
            AND customer_buys.id = ?
        ");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sel_r = $sel->get_result();
    $sel->close();

    if ($sel_r->rowCount() > 0) {

        // VALIDATION
        $validStatus = ['0', '1', '2'];

        if (in_array($st, $validStatus)) {

            // GET NEEDED INFO
            $s = $sel_r->fetch_assoc();
            $to = $s['mail'];
            $oi = $s['orderid'];

            // SET DELIVERY COSTS
            $updOrder = $pdo->prepare("UPDATE customer_buys SET paid = ? WHERE id = ?");
            $updOrder->bind_param('ss', $st, $id);
            $updOrder->execute();

            // PREPARE VERIFICATION MAIL
            $mailbody = file_get_contents('../../../../../../assets/templates/mail/orderstatuschanged.html');
            $mailsubject = 'Der Status ihrer Bestellung hat sich geändert!';
            $mailbody = str_replace('%orderid%', $oi, $mailbody);
            $mailbody = str_replace('%delcosts%', $dc, $mailbody);
            $mailheader  = $config['mail_header'];

            if ($updOrder && mail($to, $mailsubject, $mailbody, $mailheader)) {
                $pdo->commit();
                $pdo->close();
                exit('success');
            } else {
                $pdo->rollback();
                $pdo->close();
                exit('0');
            }
        } else {
            exit('2'); // STATUS INVALID
        }
    } else {
        exit('1'); // ORDER NOT EXISTING
    }
} else {
    exit;
}
