<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (
    isset($_REQUEST['pmid'], $_REQUEST['acc'])
    && is_numeric($_REQUEST['pmid'])
    && $loggedIn
) {

    $pmid = $_REQUEST['pmid'];
    $acc = $_REQUEST['acc'];
    $uid  = $my->id;

    // CHECK PMID AUTHENTICITY
    $select = $c->prepare("SELECT * FROM customer_billings WHERE id = ? AND uid = ?");
    $select->bind_param('ss', $pmid, $uid);
    $select->execute();
    $sel_r = $select->get_result();

    if ($sel_r->rowCount() > 0) {

        $s = $sel_r->fetch_assoc();
        $select->close();

        // CHECK ACCOUNT
        if ($acc === '') {
            $acc = $s['account'];
        } else {
            // CONVERT ACCOUNT NAME
            $acc = preg_replace("/ +/", " ", $acc);
            $acc = preg_replace("/^ /", "", $acc);
            $acc = preg_replace("/ $/", "", $acc);
            $acc = preg_replace("/[0-9]/", "", $acc);
            $acc = htmlspecialchars($acc);
        }

        if (preg_match('/^[A-Za-z\- ]/', $acc)) {

            $update = $c->prepare("UPDATE customer_billings SET account = ?, updated = ? WHERE id = ? AND uid = ?");
            $update->bind_param('ssss', $acc, $timestamp, $pmid, $uid);
            $update->execute();

            if ($update) {
                $c->commit();
                $update->close();
                exit('success');
            } else {
                $c->rollback();
                $update->close();
                exit('0');
            }
        } else {
            exit('2'); // Account
        }
    } else {
        exit('1'); // Not owner
    }
} else {
    exit;
}

$c->close();
