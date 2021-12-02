<?php

require_once "../../../../../../../mysql/_.session.php";
require_once "../../../../../../../mysql/_.maintenance.php";

$orderValid = ['all', 'available', 'unavailable', 'reserved', 'priceup', 'pricedown'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $loggedIn
    && $user['admin'] === '1'
) {

    $o = htmlspecialchars($_REQUEST['order']);
    $unav = false;
    $orderRes = false;

    switch ($o) {
        case 'all':
        default:
            $q = "
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products_images.isgal = '1'
                    ORDER BY products.id DESC
                ";
            break;
        case 'available':
            $q = "
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products.available = '1'
                    AND products_images.isgal = '1'
                    ORDER BY products.id DESC
                ";
            break;
        case 'unavailable':
            $q = "
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products.available = '0'
                    AND products_images.isgal = '1'
                    ORDER BY products.id DESC
                ";
            $unav = true;
            break;
        case 'reserved':
            $q = "
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products.available = '0'
                    AND products_images.isgal = '1'
                    ORDER BY products.id DESC
                ";
            $orderRes = true;
        case 'priceup':
            $q = "
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products_images.isgal = '1'
                    ORDER BY products.price ASC
                ";
            break;
        case 'pricedown':
            $q = "
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products_images.isgal = '1'
                    ORDER BY products.price DESC
                ";
    }

    $sel = $pdo->prepare($q);
    $sel->execute();
    $sel_r = $sel->get_result();

    if ($sel_r->rowCount() < 1) {

?>

        <content-card class="mb24">
            <div class="order hd-shd adjust">
                <div style="padding:82px 42px;">
                    <p class="tac">Keine Produkte zu diesem Filter</p>
                </div>

            </div>
        </content-card>

    <?php

    }

    while ($s = $sel_r->fetch_assoc()) {

        $id = $s['pid'];

        $res = false;
        $selres = $pdo->prepare("SELECT * FROM products_reserved WHERE pid = ? AND active = 1");
        $selres->bind_param('s', $id);
        $selres->execute();
        $selres_r = $selres->get_result();
        $selres->close();

        if ($selres_r->rowCount() > 0) {
            $res = true;
        }

    ?>

        <content-card class="mb24 lt tripple 
                   <?php
                    if ($res === true && $unav === true) {
                        echo 'disn';
                    } else if ($orderRes === true && $res === false) {
                        echo 'disn';
                    }
                    ?>
                ">
            <div class="products hd-shd adjust">

                <div class="image">
                    <div class="image-outer">
                        <div class="actual">
                            <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $s['url']; ?>">
                        </div>
                    </div>

                    <div class="overlay">
                        <div class="top">
                            <div class="rt">
                                <div data-element="admin-select" data-list-size="284" data-list-align="right" class="icon tran-all posrel">
                                    <p class="ac-ic">
                                        <i class="material-icons md-24">more_vert</i>
                                    </p>

                                    <datalist class="tran-all-cubic">
                                        <ul>
                                            <li class="wic" data-action="manage:products,edit" data-json='[{"id":"<?php echo $id; ?>"}]'>
                                                <p class="ic lt"><i class="material-icons md-18">edit</i></p>
                                                <p class="lt ne trimfull">Produkt bearbeiten</p>

                                                <div class="cl"></div>
                                            </li>

                                            <li class="wic" data-action="manage:product,hide" data-json='[{"id":"<?php echo $id; ?>"}]'>
                                                <p class="ic lt"><i class="material-icons md-18">visibility_off</i></p>
                                                <p class="lt ne trimfull">Produkt verbergen</p>

                                                <div class="cl"></div>
                                            </li>

                                            <div class="dist" style="width:100%;margin:12px 0;border-bottom:1px solid rgba(0,0,0,.04);"></div>

                                            <li class="wic" data-action="manage:product,delete" data-json='[{"id":"<?php echo $id; ?>"}]'>
                                                <p class="ic lt"><i class="material-icons md-18">clear</i></p>
                                                <p class="lt ne trimfull">Löschen</p>

                                                <div class="cl"></div>
                                            </li>
                                        </ul>
                                    </datalist>
                                </div>
                            </div>

                            <div class="cl"></div>
                        </div>

                        <div class="bottom">
                            <div class="price rt">
                                <p>EUR <?php echo number_format($s['price'], 2, ',', '.'); ?></p>
                            </div>

                            <div class="cl"></div>
                        </div>
                    </div>
                </div>

                <div class="inr-content">
                    <div class="name">
                        <p class="trimfull"><?php echo $s['name']; ?></p>
                    </div>

                    <div class="artnr">
                        <p class="ttup tac fw4 lt mr8">
                            <i class="material-icons md-18 lh32">bookmark</i>
                        </p>
                        <p class="ttup tac fw4 lh32 lt"><?php echo $s['artnr']; ?></p>

                        <div class="cl"></div>
                    </div>

                    <div class="av rt">

                        <?php if ($res === true) { ?>

                            <div class="av-outer o">
                                <p class="ttup">Reserviert</p>
                            </div>

                        <?php } else { ?>

                            <?php if ($s['available'] === '1') { ?>
                                <div class="av-outer g">
                                    <p class="ttup">Verfügbar</p>
                                </div>
                            <?php } else { ?>
                                <div class="av-outer r">
                                    <p class="ttup">Nicht verfügbar</p>
                                </div>
                            <?php } ?>

                        <?php } ?>
                    </div>

                    <div class="cl"></div>
                </div>

            </div>
        </content-card>

    <?php

    } // END WHILE

    ?>

    <div class="cl"></div>

<?php

} else {
    exit;
}


?>