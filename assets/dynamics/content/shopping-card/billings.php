<?php

// $bp defined in _.session

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

if (
    isset($_REQUEST['action'])
    && $_REQUEST['action'] === 'accounts-scard'
    && $loggedIn
) {

    $uid = $my->id;

    // get customers bank accounts
    $getBillings = $pdo->prepare("SELECT * FROM customer_billings WHERE uid = ?");
    $getBillings->execute([$uid]);

    // customer has no added accounts
    if ($getBillings->rowCount() < 1) {

        exit('nobank');
    } else {

?>

        <div data-element="select" data-action="paymentmethod-scard">

            <div class="select rd3 mshd-1">
                <p class="trimfull"><i class="icon-user"></i> &nbsp;&nbsp; <?php echo $bp->account; ?> &nbsp; <span style="color:#999;font-size:.8em;">IBAN &bull;&bull;&bull;&bull;<?php echo substr($bp->iban, -2); ?></span></p>
                <p class="ml8"><i class="icon-down-open-1"></i></p>
            </div>

            <div data-select="accounts" class="list multi mshd-2 rd3 tran-all-cubic">
                <ul>
                    <?php foreach ($getBillings->fetchAll() as $b) { ?>
                        <li data-json='[{"id":"<?php echo $b->id; ?>"}]'>
                            <div class="icon lt">
                                <p><i class="icon-credit-card"></i></p>
                            </div>
                            <div class="list-title rt">
                                <div class="trimfull name">
                                    <p><?php echo $b->account; ?></p>
                                </div>
                                <div class="under">
                                    <p class="trimfull"><?php echo 'BIC: ' . substr($b->bic, 0, 2) . '&bull;&bull;&bull;&bull;&bull;&bull;&bull;' . substr($b->bic, -2); ?></p>
                                </div>
                                <div class="under">
                                    <p class="trimfull iban">
                                        <?php echo 'IBAN endet auf ' . substr($b->iban, -2); ?>
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
} else {
    exit("0");
}

?>