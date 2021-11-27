<?php

// include session
include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

// start transactions for mysql
$pdo->beginTransaction();

if (
    isset($_REQUEST['id'])
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {

    $debugArray = [];
    $pmid = $_REQUEST['id'];
    $uid  = $my->id;

    // check billing method existence
    $getBilling = $pdo->prepare("SELECT * FROM customer_billings WHERE id = ? AND uid = ?");
    $getBilling->execute([$pmid, $uid]);

    if ($getBilling->rowCount() > 0) {

        // add debug
        $debugArray["billingExistence"] = true;

        // set false variables for final check
        $delete = false;
        $needNewBillingPreference = false;
        $updateBillingPreference = false;
        $queryAttributes = null;

        // set up queries for different cases
        if ($bp->pid == $pmid) {

            // add debug
            $debugArray["billingPreference"] = true;

            // true case: check for new billing method preference
            $needNewBillingPreference = true;

            // check for another billing method and update preference
            $getBilling = $pdo->prepare("SELECT * FROM customer_billings WHERE uid = ? AND id != ? ORDER BY id DESC LIMIT 1");
            $getBilling->execute([$uid, $pmid]);

            if ($getBilling->rowCount() > 0) {

                // add debug
                $debugArray["addNewBillingPreference"] = true;

                // fetch query for new billing method id
                $b = $getBilling->fetch();
                $bid = $b->id;

                // update old billing preference to new one
                $updateBillingPreference = $pdo->prepare("UPDATE customer_billings_prefs SET pid = ? WHERE uid = ?");
                $updateBillingPreference->execute([$bid, $uid]);

                // We've updated current billing preference, so just
                // delete the billing method itself
                $query = "DELETE FROM customer_billings WHERE id = ? AND uid = ?";
                $queryAttributes = [$pmid, $uid];
            } else {

                // add debug
                $debugArray["addNewBillingPreference"] = false;

                // nothing else exists, so just keep going
                $updateBillingPreference = true;

                // There's no other billing method available to set
                // to new preference.
                // Delete billing method and billing preference
                $query = "
                    DELETE customer_billings.*, customer_billings_prefs.* FROM customer_billings, customer_billings_prefs 
                    WHERE customer_billings.id = customer_billings_prefs.pid
                    AND customer_billings.uid = ? 
                    AND customer_billings.id = ?
                ";
                $queryAttributes = [$uid, $pmid];
            }
        } else {


            // add debug
            $debugArray["billingPreference"] = false;

            // just delete current billing method
            $query = "DELETE FROM customer_billings WHERE id = ? AND uid = ?";
            $queryAttributes = [$pmid, $uid];
        }

        // delete it
        $delete = $pdo->prepare($query);
        $delete->execute($queryAttributes);

        if (

            ($needNewBillingPreference && $updateBillingPreference && $delete) ||
            (!$needNewBillingPreference && !$updateBillingPreference && $delete)

        ) {

            // add debug
            $debugArray["deletedBilling"] = true;

            $pdo->commit();
            exit(json_encode($debugArray));
        } else {

            // add debug
            $debugArray["deletedBilling"] = false;

            $pdo->rollback();
            exit('0');
        }
    } else {
        exit('1'); // billing method doesn't exist/ user is not owner
    }
} else {
    exit("0");
}
