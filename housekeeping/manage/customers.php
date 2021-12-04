<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oopsie');
}

$ptit = 'Manage: Kunden';
$pid = "manage:customers";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>

<main-content class="overview">

    <!-- MAIN HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>


    <!-- MC: CONTENT -->
    <div class="mc-main">

        <div class="wide mb42">

            <!-- ALL PRODUCTS-->
            <div class="mm-heading">
                <p class="title lt lh42">Alle Kunden</p>
                <div class="tools lt ml32">
                    <div data-element="admin-select" data-action="manage:filter" data-page="customers" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                        <div class="outline disfl fldirrow">
                            <p class="text">Filtern</p>
                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                        </div>

                        <datalist class="tran-all-cubic">
                            <ul>
                                <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                                <li class="trimfull" data-json='[{"order":"verified"}]'>Verifiziert</li>
                                <li class="trimfull" data-json='[{"order":"unverified"}]'>Nicht verifiziert</li>
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

            <div data-react="manage:filter"></div>

        </div>
    </div>
</main-content>

<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>