<?php

// ERROR CODE :: 0

include_once '../../../../mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['order'], $_REQUEST['q'])
    && $_REQUEST['action'] === 'get-products'
    && $_REQUEST['q'] !== ''
) {

    $order = $_REQUEST['order'];
    $q = htmlspecialchars($_REQUEST['q']);
    $validOrder = ['id', 'priceup', 'pricedown'];

    if (!in_array($order, $validOrder)) {
        exit('0');
    }

    switch ($order) {
        case 'id':
            $order = 'id DESC';
            break;
        case 'priceup':
            $order = 'price ASC';
            break;
        case 'pricedown':
            $order = 'price DESC';
            break;
        default:
            $order = 'id DESC';
    }

    $newq = "%$q%";

    $select = $c->prepare("
            SELECT products.*, products_images.url 
            FROM products, products_images 
            WHERE products.id = products_images.pid 
            AND products_images.isgal = '1' 
            AND products.available = '1' 
            AND products.name LIKE ? 
            ORDER BY $order  
            LIMIT 10
        ");
    $select->bind_param('s', $newq);
    $select->execute();
    $select_r = $select->get_result();

    if ($select_r->rowCount() < 1) {

?>

        <div class="lh20 mt24 mb24 ph24">
            <p class="tac">Keine Produkte gefunden</p>
            <div class="disfl fldirrow jstfycc">
                <p class="fw7 trimfull" style="font-size:2em;color:#B89369;line-height:32px;padding-top:6px;"><?php echo $q; ?></p>
            </div>
        </div>

        <?php

    } else {

        while ($sel = $select_r->fetch_assoc()) {

        ?>

            <a href="/product/<?php echo $sel['artnr']; ?>" class="tran-all">
                <product-card class="mshd-1">
                    <div class="pr-inr">
                        <div class="pr-img-outer">
                            <div class="img vishid opa0 tran-all" style="background:url(<?php echo $imgurl; ?>/products/<?php echo $sel['url']; ?>) center no-repeat;background-size:cover;">
                                <img class="vishid opa0 hw1 tran-all" onload="fadeInVisOpaBg($(this).parent())" src="<?php echo $imgurl; ?>/products/<?php echo $sel['url']; ?>">
                            </div>
                        </div>

                        <div class="pr-info">
                            <p class="pr-name trimfull">
                                <?php echo $sel['name']; ?>
                            </p>
                            <p class="pr-price">
                                <?php echo number_format($sel['price'], 2, ',', '.') . ' €'; ?>
                            </p>
                        </div>
                    </div>
                </product-card>
            </a>
<?php

        }
        $select->close();
    } // END ELSE: NO PRODUCTS FOUND

} else {
    exit;
}

?>