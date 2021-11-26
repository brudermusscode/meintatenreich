<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (
    isset($_REQUEST['adid'], $_REQUEST['fullname'], $_REQUEST['country'], $_REQUEST['address'], $_REQUEST['extra'], $_REQUEST['postcode'], $_REQUEST['tel'], $_REQUEST['city'])
    && is_numeric($_REQUEST['adid'])
    && $_REQUEST['adid'] !== ''
    && $loggedIn
) {

    // CONVERT FULL NAME
    $uid = $my->id;
    $adid = $_REQUEST['adid'];
    $fname = $_REQUEST['fullname'];
    $ctr = 'germany';
    $adr = $_REQUEST['address'];
    $plz = $_REQUEST['postcode'];
    $cty = $_REQUEST['city'];
    $ext = $_REQUEST['extra'];
    $tel = $_REQUEST['tel'];

    // CHECK PMID AUTHENTICITY
    $select = $c->prepare("SELECT * FROM customer_addresses WHERE id = ? AND uid = ?");
    $select->bind_param('ss', $adid, $uid);
    $select->execute();
    $sel_r = $select->get_result();

    if ($sel_r->rowCount() > 0) {

        $s = $sel_r->fetch_assoc();
        $select->close();

        // CHECK ACCOUNT
        if ($fname === '') {
            $fname = $s['fullname'];
        } else {
            // CONVERT ACCOUNT NAME
            $fname = preg_replace("/ +/", " ", $fname);
            $fname = preg_replace("/^ /", "", $fname);
            $fname = preg_replace("/ $/", "", $fname);
            $fname = preg_replace("/[0-9]/", "", $fname);
            $fname = htmlspecialchars($fname);
        }

        // CHECK ADDRESS
        if ($adr === '') {
            $adr = $s['address'];
        }

        // CHECK POSTCODE
        if ($plz === '') {
            $plz = $s['postcode'];
        }

        // CHECK CITY
        if ($cty === '') {
            $cty = $s['city'];
        }

        // CHECK EXTRA
        if ($ext === '') {
            $ext = $s['additional'];
        }

        // CHECK TELEPHONE
        if ($tel === '') {
            $tel = $s['tel'];
        }


        if (preg_match('/^[a-zA-Z0-9.+\- äöüÄÖÜß]+$/', $adr)) {

            if (preg_match('/^[0-9]+$/', $plz)) {

                if (preg_match('/^[a-zA-Z0-9- äöüÄÖÜß]+$/', $cty)) {

                    if (preg_match('/^[a-zA-Z0-9.,+\- äöüÄÖÜß\/]+$/', $ext)) {

                        if (preg_match('/^[0-9.\/+]+$/', $tel)) {

                            if (preg_match('/^[a-zA-Z äöüÄÖÜß]+$/', $fname)) {

                                $update = $c->prepare("
                                        UPDATE customer_addresses 
                                        SET fullname = ?, address = ?, postcode = ?, city = ?, additional = ?, tel = ?, updated = ?  
                                        WHERE id = ? AND uid = ?");
                                $update->bind_param('sssssssss', $fname, $adr, $plz, $cty, $ext, $tel, $timestamp, $adid, $uid);
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
                                exit('7'); // FULL NAME
                            }
                        } else {
                            exit('6'); // TELEPHONE
                        }
                    } else {
                        exit('5'); // EXTRA
                    }
                } else {
                    exit('4'); // CITY
                }
            } else {
                exit('3'); // POSTCODE
            }
        } else {
            exit('2'); // ADDRESS
        }
    } else {
        exit('1'); // Not owner
    }
} else {
    exit;
}

$c->close();
