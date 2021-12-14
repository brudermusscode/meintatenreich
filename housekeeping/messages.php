<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";


if (!$admin->isAdmin($pdo, $my)) {
    header('location: /oops');
}

$ptit = 'Overview: Nachrichten';
$pid = "overview:messages";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>


<main-content class="messages">

    <!-- MC: HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>


    <!-- MC: CONTENT -->
    <div class="mc-main">

        <div class="wide mb42">

            <color-loader class="almid-h mt24 mb42">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-load="overview:messages" data-order="got"></div>

            <div class="cl"></div>
        </div>

    </div>
</main-content>


<?php include_once  $sroot . "/housekeeping/assets/templates/footer.php"; ?>