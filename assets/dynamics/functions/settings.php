<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (isset($_REQUEST['displayname'], $_REQUEST['firstname'], $_REQUEST['secondname'])) {

    $dname = $c->real_escape_string(htmlspecialchars($_REQUEST['displayname']));
    $fname = $c->real_escape_string(htmlspecialchars($_REQUEST['firstname']));
    $sname = $c->real_escape_string(htmlspecialchars($_REQUEST['secondname']));
    $uid   = $my->id;

    if ($dname === '') {
        if ($user['displayname'] === '') {
            $dname = 'customer-' . $login->createString(12);
        } else {
            $dname = $user['displayname'];
        }
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $dname)) {
            exit('dname');
        } else {

            $sel = $c->prepare("SELECT * FROM customer WHERE displayname = ?");
            $sel->bind_param('s', $dname);
            $sel->execute();
            $sel_r = $sel->get_result();
            $s = $sel_r->fetch_assoc();

            if ($sel_r->rowCount() > 0 && $s['id'] != $uid) {
                exit('dne');
            }
        }
    }

    if ($fname === '') {
        if ($user['firstname'] !== '') {
            $fname = $user['firstname'];
        }
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $fname)) {
            exit('fname');
        }
    }

    if ($sname === '') {
        if ($user['secondname'] !== '') {
            $sname = $user['secondname'];
        }
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $sname)) {
            exit('sname');
        }
    }

    // UPDATE CUSTOMER
    $update = $c->prepare("UPDATE customer SET displayname = ?, firstname = ?, secondname = ? WHERE id = ?");
    $update->bind_param('ssss', $dname, $fname, $sname, $uid);
    $update->execute();

    if ($update) {
        $c->commit();
        $c->close();
        exit('success');
    } else {
        $c->rollback();
        $c->close();
        exit('0');
    }

    exit($dname);
} else {

    exit;
}
