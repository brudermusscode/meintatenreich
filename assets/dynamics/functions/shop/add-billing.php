<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['acc'], $_REQUEST['bic'], $_REQUEST['iban'])
    && $_REQUEST['acc'] !== ''
    && $_REQUEST['bic'] !== ''
    && $_REQUEST['iban'] !== ''
    && $loggedIn
) {


    // remove whitespaces
    $bic = preg_replace('/\s+/', '', $_REQUEST['bic']);
    $iban = preg_replace('/\s+/', '', $_REQUEST['iban']);

    // create SEPA mandate
    $sepa = strtoupper($login->createString(6)) . '+$' . strtoupper($login->createString(6));

    // variablize
    $acc = $_REQUEST['acc'];
    $uid = $my->id;
    $pmeth = 'bank';

    // validate account holder: sign
    if (!preg_match('/[^a-z]/i', $acc)) {

        // validate bic: sign
        if (!preg_match('/[^0-9a-z]/i', $bic)) {

            // validate iban: sign
            if (!preg_match('/[^0-9]/i', $iban)) {

                // validate bic: length
                if (strlen($bic) >= 8 && strlen($bic) <= 11) {

                    // validate iban: length
                    if (strlen($iban) >= 16 && strlen($iban) <= 34) {

                        // insert new billing
                        $insert = $pdo->prepare("INSERT INTO customer_billings (uid,account,bic,iban) VALUES (?,?,?,?)");
                        $insert->execute([$uid, $acc, $bic, $iban]);

                        // CREATE VARS & RESPONSE
                        $newid = $pdo->lastInsertId();
                        $res   = ['pmid' => $newid];

                        // check for billing preference
                        $getBillingPreference = $pdo->prepare("SELECT * FROM customer_billings_prefs WHERE uid = ?");
                        $getBillingPreference->execute([$uid]);

                        $updateBillingPreference = false;
                        $insertBillingPreference = false;
                        if ($getBillingPreference->rowCount() > 0) {

                            // update billing preference
                            $updateBillingPreference = $pdo->prepare("UPDATE customer_billings_prefs SET payment = ?, pid = ?, timestamp = CURRENT_TIMESTAMP WHERE uid = ?");
                            $updateBillingPreference->execute($pmeth, $newid, $uid);
                        } else {

                            // insert new billing preference
                            $insertBillingPreference = $pdo->prepare("INSERT INTO customer_billings_prefs (uid,payment,pid) VALUES (?,?,?)");
                            $insertBillingPreference->execute([$uid, $pmeth, $newid]);
                        }

                        // INSERT SEPA
                        $insertSEPA = $pdo->prepare("INSERT INTO customer_billings_sepa (pmid,mid) VALUES (?,?)");
                        $insertSEPA->execute([$newid, $sepa]);

                        // CHECK QUERIES
                        if ($insert && ($updateBillingPreference || $insertBillingPreference) && $insertSEPA) {

                            $pdo->commit();
                            exit(json_encode($res));
                        } else {

                            $pdo->rollback();
                            exit('0');
                        }
                    } else {
                        exit('5'); // iban out of range
                    }
                } else {
                    exit('4'); // bic out of valid range
                }
            } else {
                exit('3'); // invalid iban
            }
        } else {
            exit('2'); // invalid bic
        }
    } else {
        exit('1'); // invalid account holder
    }
} else {
    exit("0");
}
