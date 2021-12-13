<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oopsie');
}

$ptit = 'Funktionen: Mailer';
$pid = "functions:mailer";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>

<main-content class="overview">

    <!-- MAIN HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>

    <!-- MC: CONTENT -->
    <div class="mc-main">
        <div class="lt left-content">

            <!-- LOADER -->
            <color-loader class="almid-h mt24 mb42 disn">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-react="func:mailer,choose" class="mb42">

                <content-card class="mb24">
                    <div class="hd-shd adjust bgf">
                        <div style="padding:82px 42px;">

                            <p class="tac mb12">
                                <i class="material-icons md-42">mark_as_unread</i>
                            </p>
                            <p class="tac">Wähle oben den Typ der Mail</p>

                        </div>
                    </div>
                </content-card>

            </div>

        </div>

    </div>
</main-content>

<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>