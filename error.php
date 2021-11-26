<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Oh oh!";
$pid = "error";
$rgname = 'Oops!';

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>


<div id="main">
    <div class="outer">
        <div class="inr">
            <div class="tac">
                <p style="font-size:3.8em;line-height:2;">Oops!</p>
                <p class="">Entweder ist diese Seite nicht mehr verfügbar oder hat nie existiert.</p>
            </div>
        </div>
    </div>
</div>


<?php

include_once $sroot . "/assets/templates/global/footer.php";

?>