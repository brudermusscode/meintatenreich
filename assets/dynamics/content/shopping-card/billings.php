<?php

// $bp defined in _.session

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$validMethods = ['bank', 'paypal'];

if (
    isset($_REQUEST['action'], $_REQUEST['which'])
    && $_REQUEST['action'] === 'accounts-scard'
    && in_array($_REQUEST['which'], $validMethods)
    && $loggedIn
) {

    $which = $_REQUEST['which'];
    $uid = htmlspecialchars($my->id);

    if ($which === 'bank') {

        // get customers bank accounts
        $getAccounts = $pdo->prepare("SELECT * FROM customer_billings WHERE uid = ?");
        $getAccounts->execute([$uid]);

        // customer has no added accounts
        if ($getAccounts->rowCount() < 1) {

            exit('nobank');
        } else {

            // GET DATA FROM PREFERENCES
            $prefData = $pdo->prepare("SELECT * FROM customer_billings WHERE uid = ? AND id = ?");
            $prefData->execute([$uid, $bp->id]);
            $bpd = $prefData->fetch();

?>

            <div data-element="select" data-action="paymentmethod-scard">
                <div class="select rd3 mshd-1">
                    <p class="trimfull"><i class="icon-user"></i> &nbsp;&nbsp; <?php echo $bpd->account; ?> &nbsp; <span style="color:#999;font-size:.8em;">IBAN &bull;&bull;&bull;&bull;<?php echo substr($bpd->iban, -2); ?></span></p>
                    <p class="ml8"><i class="icon-down-open-1"></i></p>
                </div>

                <div data-select="accounts" class="list multi mshd-2 rd3 tran-all-cubic">
                    <ul>
                        <?php foreach ($getAccounts->fetchAll() as $a) { ?>
                            <li data-json='[{"id":"<?php echo $a->id; ?>"}]'>
                                <div class="icon lt">
                                    <p><i class="icon-credit-card"></i></p>
                                </div>
                                <div class="list-title rt">
                                    <div class="trimfull name">
                                        <p><?php echo $a->account; ?></p>
                                    </div>
                                    <div class="under">
                                        <p class="trimfull"><?php echo 'BIC: ' . substr($a->bic, 0, 2) . '&bull;&bull;&bull;&bull;&bull;&bull;&bull;' . substr($a->bic, -2); ?></p>
                                    </div>
                                    <div class="under">
                                        <p class="trimfull iban">
                                            <?php echo 'IBAN endet auf ' . substr($a->iban, -2); ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="cl"></div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <input type="hidden" name="account" value="<?php echo $bp->id; ?>">
            </div>


        <?php
        }
    } else if ($which === 'paypal') {

        ?>


        <!-- TODOD: ADD FCKING PAYPAL API -->


<?php

    } else {
        exit('0');
    }
} else {
    exit("0");
}

?>