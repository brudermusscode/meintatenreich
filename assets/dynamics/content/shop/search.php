<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$legitL = ['shop', 'else'];

if (
    isset($_REQUEST['action'], $_REQUEST['q'], $_REQUEST['l'])
    && $_REQUEST['action'] === 'search'
    && $_REQUEST['q'] !== ''
    && in_array($_REQUEST['l'], $legitL)
) {

    // set variables for requested values
    $l = $_REQUEST['l'];
    $q = preg_replace('/^(\{\}+)(\[\]+)(\(\)+)(;:\,\."\'\$§\*~+)+$/i', '', $_REQUEST['q']);
    $q = htmlspecialchars($q);
    $qnew = "%$q%";

    // set display amount
    if ($l !== "shop") {
        $query = "
            SELECT products.*, products_images.url 
            FROM products, products_images 
            WHERE products.id = products_images.pid 
            AND products_images.isgal = '1' 
            AND products.name LIKE ? 
            AND products.deleted = '0' 
            ORDER BY products.price
            DESC
            LIMIT 10
        ";
    } else {
        $query = "
            SELECT products.*, products_images.url 
            FROM products, products_images 
            WHERE products.id = products_images.pid 
            AND products_images.isgal = '1' 
            AND products.deleted = '0' 
            AND products.name LIKE ?
            ORDER BY products.id
            DESC
        ";
    }

    // get all products
    $getProducts = $pdo->prepare($query);
    $getProducts->execute([$qnew]);

    // if search query is empty...
    if ($getProducts->rowCount() < 1) {

?>
        <div class="lh20 mt24 mb24 ph24">
            <p class="tac">Keine Produkte gefunden</p>
            <div class="disfl fldirrow jstfycc">
                <p class="fw7 trimfull" style="font-size:2em;color:#B89369;line-height:32px;padding-top:6px;"><?php echo $q; ?></p>
            </div>
        </div>

        <?php

    } else {

        // loop through all products
        foreach ($getProducts as $p) {

            if ($l === 'shop') {

        ?>

                <a href="/product/<?php echo $p->artnr; ?>" class="tran-all">
                    <product-card class="mshd-1">
                        <div class="pr-inr">
                            <div class="pr-img-outer">

                                <?php if ($p->available == "0") { ?>

                                    <div class="posabs rd3" style="background:rgba(0,0,0,.84);padding:8px;bottom:8px;right:12px;">
                                        <p style="color:white;font-size:.8em;font-weight:300;"><i class="icon-flash"></i> Nicht verfügbar</p>
                                    </div>

                                <?php } ?>

                                <div class="img vishid opa0 tran-all" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $p->url; ?>) center no-repeat;background-size:cover;">
                                    <img class="vishid opa0 hw1 tran-all" onload="fadeInVisOpaBg($(this).parent())" src="<?php echo $url["img"]; ?>/products/<?php echo $p->url; ?>">
                                </div>
                            </div>

                            <div class="pr-info">
                                <p class="pr-name trimfull">
                                    <?php echo $p->name; ?>
                                </p>
                                <p class="pr-price">
                                    <?php echo number_format($p->price, 2, ',', '.') . ' €'; ?>
                                </p>
                            </div>
                        </div>
                    </product-card>
                </a>

            <?php } else { ?>

                <a href="/product/<?php echo $p->artnr; ?>">
                    <div>
                        <div class="search-item disfl fldirrow jstfycflstart">
                            <p class="trimfull"><?php echo $p->name; ?></p>
                        </div>
                    </div>
                </a>

<?php

            }
        }
    }
} else {
    exit;
}

?>