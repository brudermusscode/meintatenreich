<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST["order"]) && $admin->isAdmin()) {

    $sel = $pdo->prepare("
        SELECT *, products_ratings_comments.timestamp AS pcts, products.artnr 
        FROM products_ratings_comments, products_ratings, customer, products 
        WHERE products_ratings_comments.id = products_ratings.cid 
        AND products_ratings_comments.uid = customer.id 
        AND products_ratings_comments.pid = products.id 
        ORDER BY products_ratings_comments.timestamp 
        DESC
    ");
    $sel->execute();

    if ($sel->rowCount() < 1) {

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

    foreach ($sel->fetchAll() as $elementInclude) {

        include $sroot . "/housekeeping/assets/dynamics/elements/ratings-overview.php";
    }
} else {
    exit(0);
}

?>