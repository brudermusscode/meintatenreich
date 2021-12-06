<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// valid orders
$orderValid = ['all', 'got', 'sent', 'done', 'canceled', 'unpaid', 'paid', 'paidmarked'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $admin->isAdmin()
) {

    $o = htmlspecialchars($_REQUEST['order']);

    // switch through filters
    switch ($o) {
        case 'all':
        default:
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
        case 'got':
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                AND customer_buys.status = 'got' 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
        case 'sent':
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                AND customer_buys.status = 'sent' 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
        case 'done':
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                AND customer_buys.status = 'done' 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
        case 'canceled':
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                AND customer_buys.status = 'canceled' 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
        case 'unpaid':
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                AND customer_buys.paid = '0' 
                AND customer_buys.status != 'canceled' 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
        case 'paidmarked':
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                AND customer_buys.paid = '1' 
                AND customer_buys.status != 'canceled' 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
        case 'paid':
            $q = "
                SELECT *, customer_buys.id AS oid, customer_buys_pdf.id AS pdfid
                FROM customer_buys, customer, customer_buys_pdf 
                WHERE customer_buys.uid = customer.id 
                AND customer_buys.id = customer_buys_pdf.bid 
                AND customer_buys.paid = '2' 
                AND customer_buys.status != 'canceled' 
                ORDER BY customer_buys.id
                DESC
            ";
            break;
    }

    $sel = $pdo->prepare($q);
    $sel->execute();

    if ($sel->rowCount() < 1) {

?>

        <content-card class="mb24" style="margin-bottom:200px;">
            <div class="order hd-shd adjust">
                <div style="padding:82px 42px;">
                    <p class="tac">Keine Bestellungen zu diesem Filter</p>
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