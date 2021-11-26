<?php

require_once "./mysql/_.session.php";
require_once "./mysql/_.maintenance.php";

if (!($loggedIn && isset($_GET['pr'], $_GET['del']))) {
    header('location: ./');
}

$ptit = 'Deine Bestellung war erfolgreich!';
$pid = "scard_success";
$rgname = 'Glückwunsch!';

include_once "./assets/templates/global/head.php";
include_once "./assets/templates/global/header.php";

?>


<div id="main">
    <div class="outer">
        <div class="inr">

            <div class="scard main-overflow-scroll">

                <div class="sc-inr">



                </div>

            </div>

        </div>
    </div>
</div>

<?php include_once "./assets/templates/global/footer.php"; ?>