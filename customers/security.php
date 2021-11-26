<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Sicherheit";
$pid = "security";
$rgname = 'Konto';

if (!$loggedIn) {
    header('location: ../oops');
}

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">


            <?php include_once $sroot . "/assets/templates/customers/menu.php"; ?>


            <div class="lt">
                <div class="ph42">
                    <div class="kartei-quer">
                        <div class="justify">

                            <div class="category" style="padding-top:0px;">
                                <p>Password</p>
                                <p class="trimfull">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</p>
                            </div>

                            <div class="mt24 disfl jstfycc">
                                <button data-action="open-change-password" class="hellofresh hlf-brown rd3">
                                    Passwort ändern
                                </button>

                                <div class="cl"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="cl"></div>
        </div>
    </div>
</div>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>