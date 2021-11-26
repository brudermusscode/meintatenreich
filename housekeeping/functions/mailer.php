<?php

require_once "../../mysql/_.session.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oopsie');
    }
} else {
    header('location: /oopsie');
}

$ptit = 'Funktionen: Mailer';
$pid = "func:mailer";

include_once "../assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once "../assets/templates/menu.php"; ?>

<main-content>

    <!-- MC: HEADER -->
    <?php include_once "../assets/templates/header.php"; ?>

    <!-- MC: CONTENT -->
    <div class="mc-main">
        <div class="wide">

            <div class="mm-heading" data-closeout="manage:filter">
                <p class="title lt lh42">Mailer</p>
                <div class="tools lt ml32">
                    <div data-element="admin-select" data-action="func:mailer,choose" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                        <div class="outline disfl fldirrow">
                            <p class="text">Auswählen</p>
                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                        </div>

                        <datalist class="tran-all-cubic">
                            <ul>
                                <li class="trimfull" data-json='[{"mail":"all"}]'>Rundmail</li>
                                <li class="trimfull" data-json='[{"mail":"single"}]'>Einzelmail</li>
                            </ul>
                        </datalist>
                    </div>
                </div>

                <div class="cl"></div>
            </div>

            <!-- LOADER -->
            <color-loader class="almid-h mt24 mb42 disn">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-react="func:mailer,choose" class="mb42">

                <content-card class="mb24">
                    <div class="order hd-shd adjust">
                        <div style="padding:82px 42px;">

                            <p class="tac mb12">
                                <i class="material-icons md-42">mail</i>
                            </p>
                            <p class="tac">Wähle oben den Typ der Mail</p>

                        </div>
                    </div>
                </content-card>

            </div>

        </div>

    </div>
</main-content>