<?php

    require_once "./mysql/_.session.php";
    require_once "./mysql/_.maintenance.php";

    if(isset($_GET['q'])) {
        $q = $_GET['q'];
    } else {
        exit(header('location: /'));
    }

    $ptit = "Willkommen im Shop";
    $pid = "index";
    $rgname = 'Suche';

    include_once "./assets/templates/global/head.php";
    include_once "./assets/templates/global/header.php";

?>

    <div id="main" data-json='[{"q":"<?php echo $q; ?>"}]'>
        <div class="outer">
            <div class="inr">
                
                <div class="rt disfl fldirrow">
                    <p class="lh38 mr12">Sortieren</p>
                    <div data-element="select" data-action="sort-products">
                        <div class="select rd3 mshd-1">
                            <p>Relevanteste</p>
                            <p class="ml8"><i class="icon-down-open-1"></i></p>
                        </div>

                        <div class="list mshd-2 rd3 tran-all-cubic">
                            <ul>
                                <li data-json='[{"order":"id"}]'>Relevanteste</li>
                                <li data-json='[{"order":"priceup"}]'>Preis aufsteigend</li>
                                <li data-json='[{"order":"pricedown"}]'>Preis absteigend</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="cl"></div>
                
                <div data-react="load-products" class="ovhid"></div>
            </div>
        </div>
    </div>

<script>
    
    var addClassShop = function() {
        $('body').addClass('search');
    }
    
    addClassShop();

</script>

<?php include_once "./assets/templates/global/footer.php"; ?>