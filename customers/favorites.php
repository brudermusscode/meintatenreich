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

                // GET FAVORITE PRODUCTS
                $getFavoriteProducts = $pdo->prepare("SELECT * FROM shopping_card_remember WHERE uid = ?");
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

                        // get product's information
                        $getProduct = $pdo->prepare("
                                    SELECT products.*, products.id AS pid, products_images.url 
                                    FROM products, products_images 
                                    WHERE products.id = products_images.pid 
                                    AND products_images.isgal = '1' 
                                    AND products.available = '1' 
                                    AND products.id = ?
                                ");
                        $getProduct->execute([$f->pid]);
                        $fp = $getProduct->fetch();

                        // PRODUCT ID
                        $pid = $fp->pid;

                        // CHECK RESERVATED
                        $getReservedProduct = $pdo->prepare("SELECT * FROM products_reserved WHERE pid = ? AND active = '1'");
                        $getReservedProduct->execute([$pid]);

                    ?>

                        <a href="/product/<?php echo $fp->artnr; ?>" class="tran-all">
                            <product-card class="mshd-1">

                                <div class="pr-inr">
                                    <div class="pr-img-outer posrel">

                                        <?php if ($getReservedProduct->rowCount() > 0) { ?>
                                            <div class="posabs rd3" style="background:rgba(0,0,0,.84);padding:8px;bottom:8px;right:12px;">
                                                <p style="color:white;font-size:.8em;font-weight:300;"><i class="icon-flash"></i> Reserviert</p>
                                            </div>
                                        <?php } ?>

                                        <div class="img vishid opa0 tran-all" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $fp->url; ?>) center no-repeat;background-size:cover;">
                                            <img class="vishid opa0 hw1 tran-all" onload="fadeInVisOpaBg($(this).parent())" src="<?php echo $url["img"]; ?>/products/<?php echo $fp->url; ?>">
                                        </div>
                                    </div>

                                    <div class="pr-info">
                                        <p class="pr-name trimfull">
                                            <?php echo $fp->name; ?>
                                        </p>
                                        <p class="pr-price">
                                            <?php echo number_format($fp->price, 2, ',', '.') . ' €'; ?>
                                        </p>
                                    </div>
                                </div>
                            </product-card>
                        </a>

                <?php

                    } // END WHILE
                } // END IF

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