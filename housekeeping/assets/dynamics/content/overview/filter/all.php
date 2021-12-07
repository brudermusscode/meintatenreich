<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oopsie');
}

// GET ORDERS, CUSTOMERS, RATINGS
$getAdminOverview = $pdo->prepare("SELECT * FROM admin_overview ORDER BY timestamp DESC");
$getAdminOverview->execute();

if ($getAdminOverview->rowCount() < 1) {

?>

    <content-card class="mb24" style="margin-bottom:200px;">
        <div class="order hd-shd adjust">
            <div style="padding:82px 42px;">
                <p class="tac">Hier gibt es noch nichts zu sehen! ;)</p>
            </div>

        </div>
    </content-card>

<?php

    exit;
}

foreach ($getAdminOverview->fetchAll() as $ov) {

    // type of card
    $tt = $ov->ttype;

    // id of overview card
    $elementInsertId = $ov->rid;

    // if type is an order
    if ($tt === 'order') {

        $getOrders = $pdo->prepare("
            SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
            FROM customer_buys, customer, customer_buys_pdf
            WHERE customer_buys.uid = customer.id 
            AND customer_buys.id = customer_buys_pdf.bid 
            AND customer_buys.id = ? 
            ORDER BY customer_buys.id
            DESC LIMIT 1
        ");
        $getOrders->execute([$elementInsertId]);

        foreach ($getOrders->fetchAll() as $elementInclude) {

            include $sroot . "/housekeeping/assets/dynamics/elements/orders.php";
        }
    } else if ($tt === 'customer') {

        // GET ALL ORDERS & USER INFORMATION
        $sel = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
        $sel->execute([$elementInsertId]);


        foreach ($sel->fetchAll() as $elementInclude) {

            include $sroot . "/housekeeping/assets/dynamics/elements/customers-overview.php";
        }
    } else if ($tt === 'comment') {

        $sel = $pdo->prepare("
            SELECT *, products_ratings_comments.timestamp AS pcts, products.artnr 
            FROM products_ratings_comments, products_ratings, customer, products 
            WHERE products_ratings_comments.id = products_ratings.cid 
            AND products_ratings_comments.uid = customer.id 
            AND products_ratings_comments.pid = products.id 
            AND products_ratings_comments.id = ? 
            ORDER BY products_ratings_comments.timestamp
        ");
        $sel->execute([$elementInsertId]);

        foreach ($sel->fetchAll() as $elementInclude) {

            include $sroot . "/housekeeping/assets/dynamics/elements/ratings-overview.php";
        }
    }
}

?>