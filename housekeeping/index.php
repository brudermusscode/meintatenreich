<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oops');
}

$ptit = 'Overview: Alles';
$pid = "aindex";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>


<main-content class="overview">

    <!-- MAIN HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>

    <!-- MAIN CONTENT -->
    <div class="mc-main">

        <!-- MC: LEFT -->
        <div class="lt left-content">
            <div class="mm-heading">
                <p class="title lt lh42">Übersicht</p>
                <div class="tools lt ml32">
                    <div data-element="admin-select" data-action="selector:overview,all" data-list-size="212" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                        <div class="outline disfl fldirrow">
                            <p class="text">Filtern</p>
                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                        </div>

                        <datalist class="tran-all-cubic">
                            <ul>
                                <li class="trimfull" data-json='[{"order":"#nofilter"}]'>Alle anzeigen</li>
                                <li class="trimfull" data-json='[{"order":"orders"}]'>Bestellungen</li>
                                <li class="trimfull" data-json='[{"order":"customers"}]'>Kunden</li>
                                <li class="trimfull" data-json='[{"order":"ratings"}]'>Bewertungen</li>
                            </ul>
                        </datalist>
                    </div>
                </div>

                <style>
                    .mm-heading .tools .change-view {
                        line-height: 42px;
                        padding-top: 6px;
                    }

                    .mm-heading .tools .change-view ul {
                        list-style: none;
                        display: flex;
                        flex-direction: row;
                    }

                    .mm-heading .tools .change-view ul li i {
                        list-style: none;
                        display: flex;
                        flex-direction: row;
                    }

                    .mm-heading .tools .change-view ul li {
                        opacity: .6;
                        cursor: pointer;
                    }

                    .mm-heading .tools .change-view ul li:hover {
                        opacity: 1;
                    }

                    .mm-heading .tools .change-view ul li.active {
                        opacity: 1;
                    }
                </style>

                <div class="tools rt">
                    <div class="change-view">
                        <ul>
                            <li class="tran-all"><i class="material-icons md-32">view_module</i></li>
                            <li class="tran-all active"><i class="material-icons md-32">view_stream</i></li>
                        </ul>
                    </div>
                </div>

                <div class="cl"></div>
            </div>

            <div class="mm-content" data-react="get-content:overview,all">


                <color-loader class="almid-h mt24 mb42">
                    <inr>
                        <circl3 class="color-loader1"></circl3>
                        <circl3 class="color-loader2"></circl3>
                    </inr>
                </color-loader>


            </div>
        </div>


        <!-- MC: RIGHT -->
        <div class="rt right-content">

            <div class="mm-heading">
                <p class="title lh42">Kundenübersicht</p>
            </div>

            <div class="mm-content">

                <?php

                // GET ALL ORDERS & USER INFORMATION
                $getCustomers = $pdo->prepare("SELECT * FROM customer ORDER BY id DESC");
                $getCustomers->execute();

                foreach ($getCustomers->fetchAll() as $c) {

                    $picname = false;
                    if (strlen($c->firstname) > 0 && strlen($c->secondname) > 0) {
                        $picname = true;
                        $pn = mb_substr($c->firstname, 0, 1) . mb_substr($c->secondname, 0, 1);
                    }

                ?>

                    <content-card class="mb18">
                        <div class="customer hd-shd adjust">
                            <div class="user-outer lt">
                                <div class="actual">
                                    <?php if ($picname == true) { ?>

                                        <div class="img-name">
                                            <p class=""><?php echo $pn; ?></p>
                                        </div>

                                    <?php } else { ?>

                                        <img src="<?php echo $url["img"]; ?>/elem/user.png">

                                    <?php } ?>
                                </div>
                            </div>

                            <div class="user-info rt">
                                <p class="name trimfull">

                                    <?php

                                    // CHECK NAME
                                    if (strlen($c->firstname) > 0 && strlen($c->secondname) > 0) {
                                        echo $c->firstname . ' ' . $c->secondname;
                                    } else {
                                        echo $c->displayname;
                                    }

                                    ?>

                                </p>
                                <div class="mail">
                                    <p class="icon lt">
                                        <i class="material-icons md-18">mail</i>
                                    </p>
                                    <p class="act rt trimfull"><?php echo $c->mail; ?></p>

                                    <div class="cl"></div>
                                </div>
                            </div>

                            <div class="cl"></div>
                        </div>
                    </content-card>

                <?php } ?>

            </div>
        </div>

        <div class="cl"></div>
    </div>
</main-content>


<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>