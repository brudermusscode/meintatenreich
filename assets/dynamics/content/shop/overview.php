<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['action'], $_REQUEST['order'])) {

    // set standart dropdown texts
    $dropdownCategoryText = "Wähle...";

    // check if sorted by category
    if (isset($_REQUEST['cat']) && is_numeric($_REQUEST['cat'])) {

        $reqCategoryID = htmlspecialchars($_REQUEST['cat']);
    }

    // set variables for requested values
    $action = $_REQUEST['action'];
    $order = $_REQUEST['order'];
    $validOrder = ['id', 'priceup', 'pricedown'];

    // validate sorting order
    if (!in_array($order, $validOrder)) {
        exit('0');
    }

    // switch through sorting order
    switch ($order) {
        case 'id':
            $order = 'products.id DESC';
            $dropdownOrderText   = 'Neueste zuerst';
            break;
        case 'priceup':
            $order = 'ABS(products.price) ASC';
            $dropdownOrderText   = 'Preis aufsteigend';
            break;
        case 'pricedown':
            $order = 'ABS(products.price) DESC';
            $dropdownOrderText   = 'Preis absteigend';
            break;
        default:
            $order = 'products.id DESC';
            $dropdownOrderText   = 'Neueste zuerst';
    }

    // create queries for sorting
    switch ($action) {
        case 'get-products':
            // get all products by filter
            $getProducts = $pdo->prepare("
                SELECT products.*, products.id AS pid, products_images.url 
                FROM products, products_images 
                WHERE products.id = products_images.pid 
                AND products_images.isgal = '1' 
                ORDER BY $order
            ");
            $getProducts->execute();
            break;

        case 'get-category':

            // get all products by filter
            $getProductsCategories = $pdo->prepare('SELECT * FROM products_categories WHERE id = ?');
            $getProductsCategories->execute([$reqCategoryID]);
            $category = $getProductsCategories->fetch();

            // set new filter name in dropdown
            $dropdownCategoryText = $category->category_name;

            // get all products by filter
            $getProducts = $pdo->prepare("
                SELECT products.*, products.id AS pid, products_images.url 
                FROM products, products_images 
                WHERE products.id = products_images.pid 
                AND products_images.isgal = '1' 
                AND products.cid = ?
                ORDER BY $order
            ");
            $getProducts->execute([$reqCategoryID]);
            break;

        default:
            exit("0");
    }

?>

    <!-- category chooser -->
    <div class="rt disfl fldirrow" style="margin-right:12px;margin-bottom:24px;">
        <p class="lh38 mr12">Kategorie</p>
        <div data-element="select" data-action="products:sort,category">
            <div class="select rd3 mshd-1">
                <p><?php echo $dropdownCategoryText; ?></p>
                <p class="ml8"><i class="icon-down-open-1"></i></p>
            </div>

            <div class="list mshd-2 rd3 tran-all-cubic">
                <ul>

                    <?php

                    $getProductCategories = $pdo->prepare("SELECT * FROM products_categories ORDER BY id DESC");
                    $getProductCategories->execute();

                    foreach ($getProductCategories as $pc) {

                    ?>

                        <li data-json='[{"id":"<?php echo $pc->id; ?>"}]'><?php echo $pc->category_name; ?></li>

                    <?php } ?>

                </ul>
            </div>
        </div>
    </div>


    <!-- sort chooser -->
    <div class="rt disfl fldirrow" style="margin-right:24px;margin-bottom:24px;">
        <p class="lh38 mr12">Sortieren</p>
        <div data-element="select" data-action="sort-products">
            <div class="select rd3 mshd-1">
                <p><?php echo $dropdownOrderText; ?></p>
                <p class="ml8"><i class="icon-down-open-1"></i></p>
            </div>

            <div class="list mshd-2 rd3 tran-all-cubic">
                <ul>
                    <li data-json='[{"order":"id"}]'>Neueste zuerst</li>
                    <li data-json='[{"order":"priceup"}]'>Preis aufsteigend</li>
                    <li data-json='[{"order":"pricedown"}]'>Preis absteigend</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="cl"></div>

    <?php

    if ($getProducts->rowCount() < 1) {

    ?>

        <style>
            .eye {
                height: 72px;
                width: 120px;
            }
        </style>

        <div class="sc-none mshd-1 bgf rd3">
            <div class="p42 disfl fldircol alitc jstfycc">
                <p class="eye eyes2 mb12"></p>
                <p>Keine Produkte in dieser Kategorie!</p>
            </div>
        </div>

        <?php

    } else {

        foreach ($getProducts as $p) {

            // save product ID
            $pid = $p->pid;

        ?>

            <a href="/product/<?php echo $p->artnr; ?>" class="tran-all">
                <product-card class="mshd-1">

                    <div class="pr-inr">
                        <div class="pr-img-outer posrel">

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

<?php

        }
    }
} else {
    exit('0a');
}

?>