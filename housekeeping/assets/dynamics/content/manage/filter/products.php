<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$orderValid = ['all', 'available', 'unavailable', 'reserved', 'priceup', 'pricedown'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $admin->isAdmin()
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
                SELECT * 
                FROM products_reserved, products, products_images
                WHERE products_reserved.pid = products.id
                AND products.id = products_images.pid 
                AND products_reserved.active = '1' 
                ORDER BY products_reserved.id
                DESC
            ";
            $orderRes = true;
            break;
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
            break;
    }

    $sel = $pdo->prepare($q);
    $sel->execute();

    if ($sel->rowCount() < 1) {

?>

        <content-card class="mb24">
            <div class="order hd-shd adjust">
                <div style="padding:82px 42px;">
                    <p class="tac">Keine Produkte zu diesem Filter</p>
                </div>

            </div>
        </content-card>

    <?php

        exit;
    }

    foreach ($sel->fetchAll() as $elementInclude) {

        include $sroot . "/housekeeping/assets/dynamics/elements/products.php";
    }

    ?>

    <div class="cl"></div>

<?php

} else {
    exit(0);
}


?>