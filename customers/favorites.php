<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Willkommen im Shop";
$pid = "index";
$rgname = 'Gemerkt';

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">
            <div class="main-overflow-scroll w100">

                <?php

                // get all favorized products
                $getFavoriteProducts = $pdo->prepare("
                    SELECT *, products.id AS pid
                    FROM shopping_card_remember, products, products_images
                    WHERE products_images.isgal = '1' 
                    AND products.id = shopping_card_remember.pid
                    AND products.id = products_images.pid 
                    AND shopping_card_remember.uid = ?
                ");
                $getFavoriteProducts->execute([$my->id]);

                if ($getFavoriteProducts->rowCount() < 1) {

                ?>

                    <div class="sc-none mshd-1 bgf rd3">
                        <div class="p42 disfl fldircol alitc jstfycc">
                            <p class="eye eyes2 mb12" style="height:72px;width:120px;"></p>
                            <p class="c9" style="font-size:1.2em;">Bisher nichts hinzugefügt!</p>
                        </div>
                    </div>

                    <?php

                } else {

                    foreach ($getFavoriteProducts->fetchAll() as $f) {

                        // store product id
                        $pid = $f->pid;

                    ?>

                        <a href="/product/<?php echo $f->artnr; ?>" class="tran-all">
                            <product-card class="mshd-1">

                                <div class="pr-inr">
                                    <div class="pr-img-outer posrel">

                                        <?php if ($f->available == "0") { ?>

                                            <div class="posabs rd3" style="background:rgba(0,0,0,.84);padding:8px;bottom:8px;right:12px;">
                                                <p style="color:white;font-size:.8em;font-weight:300;"><i class="icon-flash"></i> Nicht verfügbar</p>
                                            </div>

                                        <?php } ?>

                                        <div class="img vishid opa0 tran-all" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $f->url; ?>) center no-repeat;background-size:cover;">
                                            <img class="vishid opa0 hw1 tran-all" onload="fadeInVisOpaBg($(this).parent())" src="<?php echo $url["img"]; ?>/products/<?php echo $f->url; ?>">
                                        </div>
                                    </div>

                                    <div class="pr-info">
                                        <p class="pr-name trimfull">
                                            <?php echo $f->name; ?>
                                        </p>
                                        <p class="pr-price">
                                            <?php echo number_format($f->price, 2, ',', '.') . ' €'; ?>
                                        </p>
                                    </div>
                                </div>
                            </product-card>
                        </a>

                <?php

                    }
                }

                ?>

            </div>
        </div>
    </div>
</div>

<script>
    var addClassShop = function() {
        $('body').addClass('shop');
    }

    addClassShop();
</script>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>