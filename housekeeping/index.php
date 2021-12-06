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

            <color-loader class="almid-h mt24 mb42">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div class="mm-content" data-react="manage:filter"></div>
        </div>


        <!-- MC: RIGHT -->
        <div class="rt right-content" style="margin-top:-84px;">

            <div class="mm-heading" style="padding:0px 0 24px;">
                <p class="title lh42">Neue Kunden</p>
            </div>

            <div class="mm-content">

                <?php

                // GET ALL ORDERS & USER INFORMATION
                $getCustomers = $pdo->prepare("SELECT * FROM customer ORDER BY id DESC LIMIT 8");
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