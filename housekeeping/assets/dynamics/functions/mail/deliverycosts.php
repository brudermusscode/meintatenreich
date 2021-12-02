<?php


// ERROR CODE :: 0
require_once "../../../../../mysql/_.session.php";


if (isset($_REQUEST['deliverycosts'], $_REQUEST['id']) && $admin->isAdmin()) {

    $id = $_REQUEST['id'];
    $dca = $_REQUEST['deliverycosts'];

    // CHECK ORDER EXISTENCE
    $sel = $pdo->prepare("
            SELECT * 
            FROM customer_buys, customer
            WHERE customer_buys.uid = customer.id
            AND customer_buys.id = ?
            AND customer_buys.delivery = 'combi'
        ");
    $sel->bind_param('s', $id);
    $sel->execute();

    $sel->close();

    if ($sel->rowCount() > 0) {

        // GET NEEDED INFO
        $s = $sel->fetch();
        $to = $s['mail'];
        $oi = $s['orderid'];
        $dc = number_format($dca, 2, ',', '.');
        $dcdezimal = preg_replace('/[,]/', '.', $dca);

        // SET DELIVERY COSTS
        $updOrder = $pdo->prepare("UPDATE customer_buys SET price_delivery = ? WHERE id = ?");
        $updOrder->bind_param('ss', $dcdezimal, $id);
        $updOrder->execute();

        // PREPARE VERIFICATION MAIL
        $mailbody = file_get_contents('../../../../../assets/templates/mail/deliverycosts.html');
        $mailbody = str_replace('%mail%', $to, $mailbody);
        $mailbody = str_replace('%orderid%', $oi, $mailbody);
        $mailbody = str_replace('%delcosts%', $dc, $mailbody);
        $mailsubject = 'Deine Versandkosten wurden berechnet!';
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
        exit('1');
    }
} else {
    exit;
}
