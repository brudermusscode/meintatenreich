<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oopsie');
}

$ptit = 'Manage: Bewertungen';
$pid = "manage:ratings";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>

<main-content class="overview">

    <!-- MAIN HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>

    <!-- MC: CONTENT -->
    <div class="mc-main">
        <div class="wide">

            <div class="mm-heading" data-closeout="manage:filter">
                <p class="title lt lh42">Bewertungen</p>
                <div class="tools lt ml32">
                    <div data-element="admin-select" data-action="manage:filter" data-page="ratings" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                        <div class="outline disfl fldirrow">
                            <p class="text">Filtern</p>
                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                        </div>

                        <datalist class="tran-all-cubic">
                            <ul>
                                <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                            </ul>
                        </datalist>
                    </div>
                </div>

                <div class="cl"></div>
            </div>

            <color-loader class="almid-h mt24 mb42">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-react="manage:filter">



                <?php

                $sel = $pdo->prepare("
                    SELECT *, products_comments.timestamp AS pcts, products.artnr FROM products_comments, products_rating, customer, products 
                    WHERE products_comments.id = products_rating.cid 
                    AND products_comments.uid = customer.id 
                    AND products_comments.pid = products.id 
                    ORDER BY products_comments.timestamp 
                    DESC
                ");
                $sel->execute();

                if ($sel->rowCount() < 1) {

                ?>

                    <content-card class="mb24">
                        <div class="order hd-shd adjust">
                            <div style="padding:82px 42px;">
                                <p class="tac">Keine Bewertungen abgegeben</p>
                            </div>

                        </div>
                    </content-card>

                <?php

                } // END IF EMPTY

                foreach ($sel->fetchAll() as $s) {

                    // CONVERT TIMESTAMP
                    $timeAgoObject = new convertToAgo;
                    $ts = $s->pcts;
                    $convertedTime = ($timeAgoObject->convert_datetime($ts));
                    $when = ($timeAgoObject->makeAgo($convertedTime));

                ?>


                    <content-card class="mb24">

                        <div class="rating hd-shd">

                            <!-- USER ICON -->
                            <div class="user-icon">
                                <div class="actual">
                                    <div class="img-outer">
                                        <div class="img"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="rating-content rt">
                                <div class="top">
                                    <div class="name lt">
                                        <p class="trimfull">
                                            <?php

                                            // CHECK CUSTOMER NAME
                                            if (strlen($s->firstname) > 0 && strlen($s->secondname) > 0) {
                                                echo $s->firstname . ' ' . $s->secondname;
                                            } else {
                                                echo $s->displayname;
                                            }

                                            ?>
                                        </p>
                                    </div>

                                    <div class="type lt">
                                        <p>bewertung</p>
                                    </div>

                                    <div class="tools rt">
                                        <a href="/product/<?php echo $s->artnr; ?>" target="_blank">
                                            <div class="btn-outline" style="color:#FF7E8A;border-color:#FF7E8A;">
                                                <p>Zum Produkt</p>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                                <div class="middle">

                                    <div class="stars disfl fldirrow lt">

                                        <?php

                                        for ($i = 1; $i <= $s->rate; $i++) {
                                            echo '<div class="one"><i class="material-icons md-18">star</i></div>';
                                        }

                                        ?>

                                    </div>

                                    <div class="timestamp">
                                        <p>Bewertung ca. <?php echo $when; ?></p>
                                    </div>

                                    <DIV class="cl"></DIV>


                                    <div class="mail">
                                        <p class="icon lt">
                                            <i class="material-icons md-18">mail</i>
                                        </p>
                                        <p class="act rt trimfull"><?php echo $s->mail; ?></p>

                                        <div class="cl"></div>
                                    </div>

                                    <div class="actual">
                                        <p class="trimfull"><?php echo $s->text; ?></p>
                                    </div>
                                </div>

                            </div>

                            <div class="cl"></div>
                        </div>

                    </content-card>



                <?php } ?>

            </div>

        </div>

    </div>
</main-content>

<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>