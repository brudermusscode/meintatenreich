<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (
    isset($_REQUEST['id'])
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {

    $pmid = $_REQUEST['id'];
    $uid  = $my->id;

    // CHECK PMID AUTHENTICITY
    $select = $c->prepare("SELECT * FROM customer_billings WHERE id = ? AND uid = ?");
    $select->bind_param('ss', $pmid, $uid);
    $select->execute();
    $sel_r = $select->get_result();

    if ($sel_r->rowCount() > 0) {

        $select->close();

        // CHECK PREFERENCES
        $checkPref = $c->prepare("SELECT * FROM customer_billings_prefs WHERE uid = ? AND pid = ?");
        $checkPref->bind_param('ss', $uid, $pmid);
        $checkPref->execute();
        $checkPref_r = $checkPref->get_result();

        if ($checkPref_r->rowCount() > 0) {

            $checkPref->close();

            // DELETE CURRENT PREF & ACCOUNT
            $del = $c->prepare("DELETE FROM customer_billings WHERE id = ? AND uid = ?");
            $del->bind_param('ss', $pmid, $uid);
            $del->execute();

            $delPref = $c->prepare("DELETE FROM customer_billings_prefs WHERE uid = ? AND pid = ?");
            $delPref->bind_param('ss', $uid, $pmid);
            $delPref->execute();

            if ($del && $delPref) {

                // GET NEW PREF
                $select = $c->prepare("SELECT * FROM customer_billings WHERE uid = ? ORDER BY id DESC LIMIT 1");
                $select->bind_param('s', $uid);
                $select->execute();
                $sel_r = $select->get_result();

                if ($sel_r->rowCount() > 0) {

                    $nacc = $sel_r->fetch_assoc();
                    $needid = $nacc['id'];
                    $select->close();

                    $ins = $c->prepare("INSERT INTO customer_billings_prefs (uid, payment, pid, updated) VALUES (?,'bank',?,?)");
                    $ins->bind_param('sss', $uid, $needid, $timestamp);
                    $ins->execute();

                    if ($ins) {
                        $c->commit();
                        $c->close();
                        $ins->close();
                        exit('success');
                    } else {
                        $c->rollback();
                        $ins->close();
                        exit('0');
                    }
                } else {
                    $c->commit();
                    $c->close();
                    $del->close();
                    $delPref->close();
                    exit('success');
                }
            } else {
                $c->rollback();
                $c->close();
                $del->close();
                $delPref->close();
                exit('0');
            }
        } else {

            $del = $c->prepare("DELETE FROM customer_billings WHERE id = ? AND uid = ?");
            $del->bind_param('ss', $pmid, $uid);
            $del->execute();

            if ($del) {
                $c->commit();
                $c->close();
                $del->close();
                exit('success');
            } else {
                $c->rollback();
                $c->close();
                $del->close();
                exit('0');
            }
        }
    } else {

        $c->close();
        exit('1'); // Not owner
    }
} else {
    $c->close();
    exit;
}
