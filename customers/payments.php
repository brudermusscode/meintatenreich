<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Zahlungsarten";
$pid = "payments";
$rgname = 'Konto';

if (!$loggedIn) {
    header('location: ../oops');
}

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<style>
    .notice-papel {
        margin-left: 24px;
        background: url(https://statics.meintatenreich.de/img/svg/ringbuchzettel.svg) center no-repeat;
        background-size: cover;
        height: 280px;
        width: 245px;
        cursor: pointer;
        position: relative;
    }

    .notice-papel:hover {
        opacity: .8;
    }

    .notice-papel:active {
        opacity: .6;
    }

    .notice-papel .needle {
        background: url(https://statics.meintatenreich.de/img/svg/needle.svg) center no-repeat;
        background-size: cover;
        height: 58px;
        width: 58px;
        position: absolute;
        top: 0px;
        right: -4px;
        transform: rotate(90deg);
    }

    .papel-invisible {
        height: 250px;
        width: 220px;
    }

    .notice-papel .add {
        background: url(https://statics.meintatenreich.de/img/svg/metalring.svg) center no-repeat;
        background-size: cover;
        height: 58px;
        width: 58px;
        cursor: pointer;
    }

    .notice-papel .add p {
        text-align: center;
        line-height: 54px;
        font-family: 'Indie Flower', cursive;
        font-size: 2.4em;
        font-weight: 300;
        color: #D8B178;
    }

    .notice-papel .np-inr {
        padding: 42px 32px 0 40px;
    }

    .notice-papel .np-inr .add-text {
        font-size: 1em;
        color: #333;
        padding-left: 12px;
    }

    .notice-papel .np-inr .option {
        margin-bottom: 6px;
        border-bottom: 2px dashed rgba(0, 0, 0, .12);
        padding-bottom: 6px;
    }

    .notice-papel .np-inr .option:last-of-type {
        border-bottom: 0;
    }

    .notice-papel .np-inr .option p.desc {
        font-size: .8em;
        color: #B98B56;
        font-weight: 400;
    }

    .notice-papel .np-inr .option p.actual {
        font-size: 1em;
        color: #333;
    }

    .notice-papel .edit-pm {
        height: 100%;
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background: rgba(255, 255, 255, .8);
        z-index: 1;
        opacity: 0;
        visibility: hidden;
    }

    .notice-papel .edit-pm p {
        color: #B88B56;
        font-size: 3.2em;
        position: absolute;
    }

    .notice-papel:hover .edit-pm {
        opacity: 1;
        visibility: visible;
    }
</style>

<div id="main">
    <div class="outer">
        <div class="inr">


            <?php include_once  $sroot . "/assets/templates/customers/menu.php"; ?>

            <?php

            $getCustomerPayment = $pdo->prepare("SELECT * FROM customer_billings WHERE uid = ? AND active = '1' ORDER BY id DESC");
            $getCustomerPayment->execute([$my->id]);


            ?>

            <div class="main-overflow-scroll ph42 lt">
                <div data-react="add-content">
                    <?php

                    if ($getCustomerPayment->rowCount() > 0) {
                        foreach ($getCustomerPayment->fetchAll() as $p) {

                            // MANDAT INFORMATION
                            $getCustomerPaymentSEPA = $pdo->prepare("SELECT * FROM customer_billings_sepa WHERE pmid = ?");
                            $getCustomerPaymentSEPA->execute([$p->id]);
                            $ps = $getCustomerPaymentSEPA->fetch();

                    ?>

                            <div id="np-<?php echo $p->id; ?>" class="notice-papel lt" data-action="edit-payment-method" data-json='[{"uid":"<?php echo $my->id; ?>", "pmid":"<?php echo $p->id; ?>"}]'>

                                <div class="edit-pm tran-all-cubic">
                                    <p class="almid"><i class="icon-edit-3"></i></p>
                                </div>

                                <div class="needle"></div>
                                <div class="np-inr">
                                    <div class="option mt2">
                                        <p class="desc ttup">Kontoinhaber</p>
                                        <p class="actual trimfull"><?php echo $p->account; ?></p>
                                    </div>
                                    <div class="option mt2">
                                        <p class="desc ttup">BIC (Swift-Code)</p>
                                        <p class="actual trimfull"><?php echo '' . substr($p->bic, 0, 2) . '&bull;&bull;&bull;&bull;&bull;&bull;&bull;' . substr($p->bic, -2); ?></p>
                                    </div>
                                    <div class="option mt2">
                                        <p class="desc ttup">IBAN</p>
                                        <p class="actual trimfull"><?php echo 'Endet auf ' . substr($p->iban, -2); ?></p>
                                    </div>
                                    <div class="option mt2">
                                        <p class="desc ttup">Mandat ID</p>
                                        <p class="actual trimfull"><?php echo $ps->mid; ?></p>
                                    </div>

                                </div>
                            </div>

                    <?php }
                    } ?>

                    <div class="notice-papel lt" data-action="add-payment-method">
                        <div class="needle"></div>
                        <div class="np-inr">
                            <p class="add-text tac mt24">Bankverbindung hinzufügen</p>
                            <div class="add almid-h mt24">
                                <p>+</p>
                            </div>
                        </div>
                    </div>

                    <div class="cl"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include_once  $sroot . "/assets/templates/global/footer.php"; ?>