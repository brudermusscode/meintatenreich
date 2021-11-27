<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();
header('Content-type: application/json');

if (
    isset($_REQUEST['fullname'], $_REQUEST['country'], $_REQUEST['str'], $_REQUEST['housenumber'], $_REQUEST['extra'], $_REQUEST['postcode'], $_REQUEST['tel'], $_REQUEST['city'])
    && !empty($_REQUEST['fullname'])
    && !empty($_REQUEST['country'])
    && !empty($_REQUEST['str'])
    && !empty($_REQUEST['housenumber'])
    && !empty($_REQUEST['postcode'])
    && !empty($_REQUEST['city'])
    && !empty($_REQUEST['tel'])
    && $loggedIn
) {

    // CONVERT FULL NAME
    $fullname = preg_replace("/ +/", " ", $_REQUEST['fullname']);
    $fullname = preg_replace("/^ /", "", $fullname);
    $fullname = preg_replace("/ $/", "", $fullname);
    $fullname = htmlspecialchars($fullname);

    $uid = $my->id;
    $country = 'germany';
    $street = $_REQUEST['str'];
    $number = $_REQUEST['housenumber'];
    $postcode = $_REQUEST['postcode'];
    $city = $_REQUEST['city'];
    $extra = $_REQUEST['extra'];
    $telephone = $_REQUEST['tel'];

    // SET EXTRA
    if ($extra == '') {
        $extra = "";
    }

    // validate street
    if (!preg_match("/[^a-z0-9+äöüÄÖÜß.\-\s]/i", $street)) {

        // validate hausnummer
        if (!preg_match("/[^a-z0-9\s]/i", $number)) {

            // bind street and number as address
            $address = $street . ' ' . $number;

            // validate postcode
            if (!preg_match("/[^0-9]/i", $postcode)) {

                // validate city
                if (!preg_match("/[^a-z\-äöüÄÖÜß\s]/i", $city)) {

                    // validate extra
                    if (!preg_match("/[^a-z0-9+.äöüÄÖÜß\s\-]/i", $extra)) {

                        // validate telephone
                        if (!preg_match("/[^0-9+\/\s]/i", $telephone)) {

                            // insert new address
                            $insertAddress = $pdo->prepare("
                                INSERT INTO customer_addresses 
                                (uid,fullname,country,address,additional,postcode,city,tel) 
                                VALUES (?,?,?,?,?,?,?,?)
                            ");
                            $insertAddress->execute([$uid, $fullname, $country, $address, $extra, $postcode, $city, $telephone]);

                            // get id of newly added address
                            // and store in array
                            $newid = $pdo->lastInsertId();
                            $res   = [
                                'adid' => $newid,
                                "street" => $street,
                                "housenumber" => $number
                            ];

                            $updateAddressPreference = false;
                            $insertAddressPreference = false;
                            if ($my->addressPreference = true) {

                                // update address preferences
                                $updateAddressPreference = $pdo->prepare("UPDATE customer_addresses_prefs SET adid = ? WHERE uid = ?");
                                $updateAddressPreference->execute([$newid, $uid]);
                            } else {

                                // insert address preferences
                                $insertAddressPreference = $pdo->prepare("INSERT INTO customer_addresses_prefs (uid,adid) VALUES (?,?)");
                                $insertAddressPreference->execute([$uid, $newid]);
                            }

                            if ($insertAddress && ($updateAddressPreference || $insertAddressPreference)) {

                                $pdo->commit();
                                exit(json_encode($res));
                            } else {

                                $pdo->rollback();
                                exit('0');
                            }
                        } else {
                            exit('6'); // invalid telephone number
                        }
                    } else {
                        exit('5'); // invalid extra stuff for hausnummer
                    }
                } else {
                    exit('4'); // invalid city
                }
            } else {
                exit('3'); // invalid postcode
            }
        } else {
            exit('2'); // invalid hausnummer
        }
    } else {
        exit('1'); // street invalid
    }
} else {

    exit("0");
}
