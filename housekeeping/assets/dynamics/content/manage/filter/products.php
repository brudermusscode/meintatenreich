<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$orderValid = ['all', 'available', 'unavailable', 'priceup', 'pricedown', 'archived'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $admin->isAdmin()
) {

    $o = $_REQUEST['order'];
    $unav = false;

    // switch through all filtering cases and create query which will be passed
    // in prepared statement down under
    switch ($o) {

        default:
        case 'all':
            $q = "
                SELECT *, products.id AS pid 
                FROM products, products_images 
                WHERE products.id = products_images.pid
                AND products_images.isgal = '1'
                AND products.deleted = '0' 
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
                AND products.deleted = '0' 
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
                AND products.deleted = '0' 
                ORDER BY products.id DESC
            ";
            $unav = true;
            break;
        case 'priceup':
            $q = "
                SELECT *, products.id AS pid 
                FROM products, products_images 
                WHERE products.id = products_images.pid
                AND products_images.isgal = '1'
                AND products.deleted = '0' 
                ORDER BY products.price ASC
            ";
            break;
        case 'pricedown':
            $q = "
                SELECT *, products.id AS pid 
                FROM products, products_images 
                WHERE products.id = products_images.pid
                AND products_images.isgal = '1'
                AND products.deleted = '0' 
                ORDER BY products.price DESC
            ";
            break;
        case 'archived':
            $q = "
                SELECT *, products.id AS pid 
                FROM products, products_images 
                WHERE products.id = products_images.pid
                AND products_images.isgal = '1'
                AND products.deleted = '1' 
                ORDER BY products.price DESC
            ";
            break;
    }

    // query all products
    $sel = $pdo->prepare($q);
    $sel->execute();

    // if there aren't any products for the chosen filter
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

        // exit out of the script since we don't want to show anything else than
        // the content that no products are available
        exit;
    }

    // ... else go and foreach loop everything that was found in the database
    foreach ($sel->fetchAll() as $elementInclude) {

        // use product element for looping to keep a slim code
        include $sroot . "/housekeeping/assets/dynamics/elements/products.php";
    }

    ?>

    <div class="cl"></div>

<?php

} else {
    exit(0);
}


?>