<?php

require_once "./mysql/_.session.php";
require_once "./mysql/_.maintenance.php";

$ptit = "Willkommen im Shop";
$pid = "index";
$rgname = 'Gemerkt';

include_once "./assets/templates/global/head.php";
include_once "./assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">
            <div class="main-overflow-scroll w100">

                <?php

                // GET FAVORITE PRODUCTS
                $sel = $c->prepare("SELECT * FROM scard_remember WHERE uid = ?");
                $sel->bind_param('s', $my->id);
                $sel->execute();
                $sel_r = $sel->get_result();
                $sel->close();

                if ($sel_r->rowCount() < 1) {

                ?>

                    <div class="sc-none mshd-1 bgf rd3">
                        <div class="p42 disfl fldircol alitc jstfycc">
                            <p class="eye eyes2 mb12" style="height:72px;width:120px;"></p>
                            <p class="c9" style="font-size:1.2em;">Bisher nichts hinzugefügt!</p>
                        </div>
                    </div>

                    <?php

                } else {

                    while ($s = $sel_r->fetch_assoc()) {

                        // GET PRODUCT INFORMATION
                        $selProd = $c->prepare("
                                    SELECT products.*, products.id AS pid, products_images.url 
                                    FROM products, products_images 
                                    WHERE products.id = products_images.pid 
                                    AND products_images.isgal = '1' 
                                    AND products.available = '1' 
                                    AND products.id = ?
                                ");
                        $selProd->bind_param('s', $s['pid']);
                        $selProd->execute();
                        $selProd_r = $selProd->get_result();
                        $selProd->close();
                        $prd = $selProd_r->fetch_assoc();

                        // PRODUCT ID
                        $pid = $prd['pid'];

                        // CHECK RESERVATED
                        $checkRes = $c->prepare("SELECT * FROM products_reserved WHERE pid = ? AND active = '1'");
                        $checkRes->bind_param('s', $pid);
                        $checkRes->execute();
                        $checkRes_r = $checkRes->get_result();
                        $checkRes->close();

                    ?>

                        <a href="/product/<?php echo $prd['artnr']; ?>" class="tran-all">
                            <product-card class="mshd-1">

                                <div class="pr-inr">
                                    <div class="pr-img-outer posrel">

                                        <?php if ($checkRes_r->rowCount() > 0) { ?>
                                            <div class="posabs rd3" style="background:rgba(0,0,0,.84);padding:8px;bottom:8px;right:12px;">
                                                <p style="color:white;font-size:.8em;font-weight:300;"><i class="icon-flash"></i> Reserviert</p>
                                            </div>
                                        <?php } ?>

                                        <div class="img vishid opa0 tran-all" style="background:url(<?php echo $imgurl; ?>/products/<?php echo $prd['url']; ?>) center no-repeat;background-size:cover;">
                                            <img class="vishid opa0 hw1 tran-all" onload="fadeInVisOpaBg($(this).parent())" src="<?php echo $imgurl; ?>/products/<?php echo $prd['url']; ?>">
                                        </div>
                                    </div>

                                    <div class="pr-info">
                                        <p class="pr-name trimfull">
                                            <?php echo $prd['name']; ?>
                                        </p>
                                        <p class="pr-price">
                                            <?php echo number_format($prd['price'], 2, ',', '.') . ' €'; ?>
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

<?php include_once "./assets/templates/global/footer.php"; ?>