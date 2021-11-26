<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (
    isset($_REQUEST['fullname'], $_REQUEST['country'], $_REQUEST['str'], $_REQUEST['hnr'], $_REQUEST['extra'], $_REQUEST['postcode'], $_REQUEST['tel'], $_REQUEST['city'])
    && $_REQUEST['fullname'] !== ''
    && $_REQUEST['country'] !== ''
    && $_REQUEST['str'] !== ''
    && $_REQUEST['hnr'] !== ''
    && $_REQUEST['postcode'] !== ''
    && $_REQUEST['city'] !== ''
    && $_REQUEST['tel'] !== ''
    && $loggedIn
) {

    // CONVERT FULL NAME
    $fname = preg_replace("/ +/", " ", $_REQUEST['fullname']);
    $fname = preg_replace("/^ /", "", $fname);
    $fname = preg_replace("/ $/", "", $fname);
    $fname = htmlspecialchars($fname);

    $uid = $my->id;
    $ctr = 'germany';
    $str = $_REQUEST['str'];
    $hnr = $_REQUEST['hnr'];
    $plz = $_REQUEST['postcode'];
    $cty = $_REQUEST['city'];
    $ext = $_REQUEST['extra'];
    $tel = $_REQUEST['tel'];

    // SET EXTRA
    if ($ext === '') {
        $ext = 'none';
    }

    if (preg_match("/^[a-zA-Z0-9.+\- äöüÄÖÜß]+$/", $str)) {
        if (preg_match("/^[a-zA-Z0-9 ]+$/", $hnr)) {

            // BIND ADDRESS
            $adr = $str . ' ' . $hnr;

            if (preg_match("/^[0-9]+$/", $plz)) {
                if (preg_match("/^[a-zA-Z\- äöüÄÖÜß]+$/", $cty)) {
                    if (preg_match("/^[a-zA-Z0-9+\-. äöüÄÖÜß]+$/", $ext)) {
                        if (preg_match("/^[0-9+ \/]+$/", $tel)) {

                            // INSERT ADDRESS
                            $address = $c->prepare(
                                "INSERT INTO customer_addresses 
                                    (uid,fullname,country,address,additional,postcode,city,tel,updated) 
                                    VALUES (?,?,?,?,?,?,?,?,?)"
                            );
                            $address->bind_param('sssssssss', $uid, $fname, $ctr, $adr, $ext, $plz, $cty, $tel, $timestamp);
                            $address->execute();

                            // SET VARS & RESPONSE
                            $newid = $address->insert_id;
                            $res   = ['adid' => $newid];

                            // CHECK PREFS
                            $sel = $c->prepare("SELECT * FROM customer_addresses_prefs WHERE uid = ?");
                            $sel->bind_param('s', $uid);
                            $sel->execute();
                            $s_r = $sel->get_result();

                            if ($s_r->rowCount() > 0) {

                                // UPDATE PREFERENCES
                                $pref = $c->prepare("UPDATE customer_addresses_prefs SET adid = ?, updated = ? WHERE uid = ?");
                                $pref->bind_param('sss', $newid, $timestamp, $uid);
                                $pref->execute();
                            } else {

                                // INSERT PREFERENCES
                                $pref = $c->prepare("INSERT INTO customer_addresses_prefs (uid,adid,updated) VALUES (?,?,?)");
                                $pref->bind_param('sss', $uid, $newid, $timestamp);
                                $pref->execute();
                            }

                            // CHECK IF QUERY WAS SUCCESSFUL
                            if ($address && $pref) {
                                $c->commit();
                                $c->close();
                                $address->close();
                                $pref->close();
                                $sel->close();
                                header('Content-type: application/json');
                                exit(json_encode($res));
                            } else {
                                $c->rollback();
                                $c->close();
                                $address->close();
                                $pref->close();
                                $sel->close();
                                exit('0');
                            }
                        } else {
                            exit('6');
                        }
                    } else {
                        exit('5');
                    }
                } else {
                    exit('4');
                }
            } else {
                exit('3');
            }
        } else {
            exit('2');
        }
    } else {
        exit('1');
    }
} else {

    exit;
}
