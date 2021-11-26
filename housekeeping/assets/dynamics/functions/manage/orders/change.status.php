<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['status'], $_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $id = $_REQUEST['id'];
    $st = $_REQUEST['status'];

    // CHECK ORDER EXISTENCE
    $sel = $c->prepare("
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
        $validStatus = ['got', 'sent', 'done', 'canceled'];

        if (in_array($st, $validStatus)) {

            // GET NEEDED INFO
            $s = $sel_r->fetch_assoc();
            $to = $s['mail'];
            $oi = $s['orderid'];

            // SET DELIVERY COSTS
            $updOrder = $c->prepare("UPDATE customer_buys SET status = ?, updated = ? WHERE id = ?");
            $updOrder->bind_param('sss', $st, $timestamp, $id);
            $updOrder->execute();

            // PREPARE VERIFICATION MAIL
            if ($st === 'sent') {
                $mailbody = file_get_contents('../../../../../../assets/templates/mail/orderdelivered.html');
                $mailsubject = 'Ihre Bestellung wurde versandt!';
            } else {
                $mailbody = file_get_contents('../../../../../../assets/templates/mail/orderstatuschanged.html');
                $mailsubject = 'Der Status ihrer Bestellung hat sich geändert!';
            }
            $mailbody = str_replace('%orderid%', $oi, $mailbody);
            $mailbody = str_replace('%delcosts%', $dc, $mailbody);
            $mailheader  = $config['mail_header'];

            // VARIOUS CASES
            $pids = [];
            $selProducts = $c->prepare("SELECT pid FROM customer_buys_products WHERE bid = ?");
            $selProducts->bind_param('s', $id);
            $selProducts->execute();
            $selProducts_r = $selProducts->get_result();
            $selProducts->close();
            while ($p = $selProducts_r->fetch_assoc()) {
                $pids[] = $p['pid'];
            }

            $updProducts = true;
            $delRes = true;
            if ($st === 'done') {

                foreach ($pids as $pid) {

                    // MAKE PRODUCTS UNAVAILABLE
                    $updProducts = $c->prepare("UPDATE products SET available = '0' WHERE id = ?");
                    $updProducts->bind_param('s', $pid);
                    $updProducts->execute();

                    // REMOVE RESERVATIONS
                    $delRes = $c->prepare("DELETE FROM products_reserved WHERE pid = ?");
                    $delRes->bind_param('s', $pid);
                    $delRes->execute();
                }
            } else if ($st === 'canceled') {

                foreach ($pids as $pid) {

                    // REMOVE RESERVATIONS
                    $delRes = $c->prepare("DELETE FROM products_reserved WHERE pid = ?");
                    $delRes->bind_param('s', $pid);
                    $delRes->execute();
                }
            }

            if ($updOrder && $delRes && $updProducts && mail($to, $mailsubject, $mailbody, $mailheader)) {
                $c->commit();
                $c->close();
                exit('success');
            } else {
                $c->rollback();
                $c->close();
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
