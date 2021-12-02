<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_GET['q'])) {
    $q = $_GET['q'];
} else {
    exit(header('location: /'));
}

$ptit = "Willkommen im Shop";
$pid = "index";
$rgname = 'Suche';

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main" data-json='[{"q":"<?php echo $q; ?>"}]'>
    <div class="outer">
        <div class="inr">

            <input type="hidden" class="vishid opa0" data-ability="keep" data-input="products:sort,order" value="id">
            <div data-react="load-products" class="main-overflow-scroll w100"></div>
        </div>
    </div>
</div>

<script>
    var addClassShop = function() {
        $('body').addClass('search');
    }

    addClassShop();
</script>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>