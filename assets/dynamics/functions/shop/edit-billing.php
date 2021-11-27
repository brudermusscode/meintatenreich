<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['pmid'], $_REQUEST['acc'])
    && is_numeric($_REQUEST['pmid'])
    && $loggedIn
) {

    $pmid = $_REQUEST['pmid'];
    $acc = $_REQUEST['acc'];
    $uid  = $my->id;

    // check if billing method exists/belongs to user
    $getBilling = $pdo->prepare("SELECT * FROM customer_billings WHERE id = ? AND uid = ?");
    $getBilling->execute([$pmid, $uid]);

    if ($getBilling->rowCount() > 0) {

        $b = $getBilling->fetch();

        if ($acc === '') {

            $acc = $b->account;
        }

        // validate account
        if ($shop->validateName($acc)) {

            $updateBilling = $pdo->prepare("UPDATE customer_billings SET account = ?, timestamp = CURRENT_TIMESTAMP WHERE id = ? AND uid = ?");
            $updateBilling->execute([$acc, $pmid, $uid]);

            if ($updateBilling) {

                $pdo->commit();
                exit($acc);
            } else {

                $pdo->rollback();
                exit('0');
            }
        } else {
            exit('2'); // invalid account format
        }
    } else {
        exit('0'); // billing metzhod doesn't exist or doesn't belong to user
    }
} else {
    exit("0");
}
