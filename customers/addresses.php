<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Adressen";
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
        margin-bottom: 8px;
        border-bottom: 2px dashed rgba(0, 0, 0, .12);
        padding-bottom: 12px;
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


            <?php include_once $sroot . "/assets/templates/customers/menu.php"; ?>

            <?php

            $getCustomerAdresses = $pdo->prepare("SELECT * FROM customer_addresses WHERE uid = ? AND active = '1' ORDER BY id DESC");
            $getCustomerAdresses->execute([$my->id]);


            ?>

            <div class="main-overflow-scroll lt mysection">
                <div data-react="add-content">
                    <?php

                    if ($getCustomerAdresses->rowCount() > 0) {
                        foreach ($getCustomerAdresses->fetchAll() as $c) {


                    ?>

                            <div id="np-<?php echo $c->id; ?>" class="notice-papel lt" data-action="edit-address" data-json='[{"adid":"<?php echo $c->id; ?>", "uid":"<?php echo $c->id; ?>"}]'>

                                <div class="edit-pm tran-all-cubic">
                                    <p class="almid"><i class="icon-edit-3"></i></p>
                                </div>

                                <div class="needle"></div>
                                <div class="np-inr">
                                    <div class="option mt12">
                                        <p class="desc ttup  w100">NAME</p>
                                        <p class="actual trimfull w100"><?php echo $c->fullname; ?></p>
                                    </div>
                                    <div class="option mt12">
                                        <p class="desc ttup  w100">Anschrift</p>
                                        <p class="actual trimfull w100">
                                            <?php
                                            if ($c->additional !== '' && $c->additional !== 'none') {
                                                echo $c->address . ', ' . $c->additional;
                                            } else {
                                                echo $c->address;
                                            }
                                            ?>
                                        </p>
                                        <p class="actual trimfull w100"><?php echo $c->postcode . ' ' . $c->city; ?></p>
                                    </div>
                                    <div class="option mt12">
                                        <p class="desc ttup  w100">Telefonnummer</p>
                                        <p class="actual trimfull w100"><?php echo $c->tel; ?></p>
                                    </div>

                                </div>
                            </div>

                    <?php }
                    } ?>

                    <div class="notice-papel lt" data-action="add-address">
                        <div class="needle"></div>
                        <div class="np-inr">
                            <p class="add-text tac mt24">Lieferadresse hinzuf??gen</p>
                            <div class="add almid-h mt24">
                                <p>+</p>
                            </div>
                        </div>
                    </div>

                    <div class="cl"></div>
                </div>
            </div>

            <div class="cl"></div>

        </div>
    </div>
</div>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>