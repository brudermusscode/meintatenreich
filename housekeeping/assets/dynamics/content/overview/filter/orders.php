<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST["order"]) && $admin->isAdmin()) {

    // GET ALL ORDERS & USER INFORMATION
    $sel = $pdo->prepare("
        SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
        FROM customer_buys, customer, customer_buys_pdf 
        WHERE customer_buys.uid = customer.id 
        AND customer_buys.id = customer_buys_pdf.bid 
        ORDER BY customer_buys.timestamp
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

        include $sroot . "/housekeeping/assets/dynamics/elements/orders.php";
    }
} else {
    exit(0);
}
?>