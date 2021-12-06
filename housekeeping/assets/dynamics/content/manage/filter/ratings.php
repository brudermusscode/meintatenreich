<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST["order"]) && $admin->isAdmin()) {

    $sel = $pdo->prepare("
        SELECT *, products_comments.timestamp AS pcts, products.artnr FROM products_comments, products_rating, customer, products 
        WHERE products_comments.id = products_rating.cid 
        AND products_comments.uid = customer.id 
        AND products_comments.pid = products.id 
        ORDER BY products_comments.timestamp 
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

        // CONVERT TIMESTAMP
        $timeAgoObject = new convertToAgo;
        $ts = $s->pcts;
        $convertedTime = ($timeAgoObject->convert_datetime($ts));
        $when = ($timeAgoObject->makeAgo($convertedTime));


        include $sroot . "/housekeeping/assets/dynamics/elements/ratings-overview.php";
    }
} else {
    exit(0);
}

?>