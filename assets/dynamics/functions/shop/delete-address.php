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
    $adid = $_REQUEST['id'];
    $uid  = $my->id;

    // check address existence
    $getAddress = $pdo->prepare("SELECT * FROM customer_addresses WHERE id = ? AND uid = ?");
    $getAddress->execute([$adid, $uid]);

    if ($getAddress->rowCount() > 0) {

        // add debug
        $debugArray["addressExistence"] = true;

        // set false variables for final check
        $delete = false;
        $needNewAddressPreference = false;
        $updateAddressPreference = false;
        $queryAttributes = null;

        // set up queries for different cases
        if ($ap->adid == $adid) {

            // add debug
            $debugArray["addressPreference"] = true;

            // true case: check for new address preference
            $needNewAddressPreference = true;

            // check for another address and update preference
            $getAddress = $pdo->prepare("SELECT * FROM customer_addresses WHERE uid = ? AND id != ? ORDER BY id DESC LIMIT 1");
            $getAddress->execute([$uid, $adid]);

            if ($getAddress->rowCount() > 0) {

                // add debug
                $debugArray["addNewAddressPreference"] = true;

                // fetch query for new address id
                $a = $getAddress->fetch();
                $aid = $a->id;

                // update old address preference to new one
                $updateAddressPreference = $pdo->prepare("UPDATE customer_addresses_prefs SET adid = ? WHERE uid = ?");
                $updateAddressPreference->execute([$aid, $uid]);

                // We've updated current address preference, so just
                // delete the address method itself
                $query = "DELETE FROM customer_addresses WHERE id = ? AND uid = ?";
                $queryAttributes = [$adid, $uid];
            } else {

                // add debug
                $debugArray["addNewAddressPreference"] = false;

                // nothing else exists, so just keep going
                $updateAddressPreference = true;

                // There's no other address available to set
                // to new preference.
                // Delete address and address preference
                $query = "
                    DELETE customer_addresses.*, customer_addresses_prefs.* FROM customer_addresses, customer_addresses_prefs 
                    WHERE customer_addresses.id = customer_addresses_prefs.adid
                    AND customer_addresses.uid = ? 
                    AND customer_addresses.id = ?
                ";
                $queryAttributes = [$uid, $adid];
            }
        } else {


            // add debug
            $debugArray["addressPreference"] = false;

            // just delete current address
            $query = "DELETE FROM customer_addresses WHERE id = ? AND uid = ?";
            $queryAttributes = [$adid, $uid];
        }

        // delete it
        $delete = $pdo->prepare($query);
        $delete->execute($queryAttributes);

        if (

            ($needNewAddressPreference && $updateAddressPreference && $delete) ||
            (!$needNewAddressPreference && !$updateAddressPreference && $delete)

        ) {

            // add debug
            $debugArray["deletedAddress"] = true;

            $pdo->commit();
            exit(json_encode($debugArray));
        } else {

            // add debug
            $debugArray["deletedAddress"] = false;

            $pdo->rollback();
            exit('0');
        }
    } else {
        exit('1'); // address doesn't exist/ user is not owner
    }
} else {
    exit("0");
}
