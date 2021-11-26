<?php

require_once "../../../../../../../mysql/_.session.php";
require_once "../../../../../../../mysql/_.maintenance.php";

$orderValid = ['all', 'got', 'sent', 'done', 'canceled', 'unpaid', 'paid', 'paidmarked'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $loggedIn
    && $user['admin'] === '1'
) {

    $o = htmlspecialchars($_REQUEST['order']);

    switch ($o) {
        case 'all':
        default:
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    ORDER BY customer_buys.id
                    DESC
                ";
            break;
        case 'got':
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    AND customer_buys.status = 'got' 
                    ORDER BY customer_buys.id
                    DESC
                ";
            break;
        case 'sent':
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    AND customer_buys.status = 'sent' 
                    ORDER BY customer_buys.id
                    DESC
                ";
            break;
        case 'done':
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    AND customer_buys.status = 'done' 
                    ORDER BY customer_buys.id
                    DESC
                ";
            break;
        case 'canceled':
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    AND customer_buys.status = 'canceled' 
                    ORDER BY customer_buys.id
                    DESC
                ";
            break;
        case 'unpaid':
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    AND customer_buys.paid = '0' 
                    AND customer_buys.status != 'canceled' 
                    ORDER BY customer_buys.id
                    DESC
                ";
            break;
        case 'paidmarked':
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    AND customer_buys.paid = '1' 
                    AND customer_buys.status != 'canceled' 
                    ORDER BY customer_buys.id
                    DESC
                ";
            break;
        case 'paid':
            $q = "
                    SELECT *, customer_buys.id AS oid 
                    FROM customer_buys, customer 
                    WHERE customer_buys.uid = customer.id 
                    AND customer_buys.paid = '2' 
                    AND customer_buys.status != 'canceled' 
                    ORDER BY customer_buys.id
                    DESC
                ";
    }

    $sel = $c->prepare($q);
    $sel->execute();
    $sel_r = $sel->get_result();
    $sel->close();

    if ($sel_r->rowCount() < 1) {

?>

        <content-card class="mb24">
            <div class="order hd-shd adjust">
                <div style="padding:82px 42px;">
                    <p class="tac">Keine Bestellungen zu diesem Filter</p>
                </div>

            </div>
        </content-card>

    <?php

    }

    while ($s = $sel_r->fetch_assoc()) {

        // GET BILL PDF ID
        $sel = $c->prepare("
                SELECT * FROM customer_buys_pdf
                WHERE bid = ?
            ");
        $sel->bind_param('s', $s['oid']);
        $sel->execute();
        $sr = $sel->get_result();
        $pdf = $sr->fetch_assoc();
        $sel->close();

    ?>

        <content-card class="mb24">
            <div class="order hd-shd adjust">

                <!-- USER ICON -->
                <div class="user-icon lt disn">
                    <div class="actual">
                        <div class="img-outer">
                            <div class="img"></div>
                        </div>
                    </div>
                </div>

                <!-- CONTENT -->
                <div class="order-content rt">

                    <div class="top">
                        <div class="name lt">
                            <p class="trimfull">

                                <?php

                                // CHECK CUSTOMER NAME
                                if (strlen($s['firstname']) > 0 && strlen($s['secondname']) > 0) {
                                    echo $s['firstname'] . ' ' . $s['secondname'];
                                } else {
                                    echo $s['displayname'];
                                }

                                ?>

                            </p>
                        </div>
                        <div class="type lt">
                            <p>Bestellung</p>
                        </div>
                        <div class="top-right">

                            <div class="rt status">
                                <div data-element="admin-select" data-list-align="right" data-list-size="328" style="height:42px;width:42px;position:relative;overflow:hdden;" class="tran-all">
                                    <div class="outline disfl fldirrow" style="border:0;width:100%;height:100%;padding:0;margin:0;">
                                        <p class="icon"><i class="material-icons md-24 lh42">more_vert</i></p>
                                    </div>

                                    <datalist class="tran-all-cubic">
                                        <ul>
                                            <li class="wic" data-action="manage:order" data-json='[{"id":"<?php echo $s['orderid']; ?>"}]'>
                                                <p class="ic lt"><i class="material-icons md-18">build</i></p>
                                                <p class="lt ne trimfull">Bestellung verwalten</p>

                                                <div class="cl"></div>
                                            </li>

                                            <li class="wic" data-action="manage:customers,orders" data-json='[{"id":"<?php echo $s['uid']; ?>"}]'>
                                                <p class="ic lt"><i class="material-icons md-18">widgets</i></p>
                                                <p class="lt ne trimfull">Alle Bestellungen des Kunden</p>

                                                <div class="cl"></div>
                                            </li>

                                            <a href="/a/bill/<?php echo $pdf['id']; ?>" target="_blank" style="color:rgb(80, 104, 161);">
                                                <li class="wic">
                                                    <p class="ic lt"><i class="material-icons md-18">description</i></p>
                                                    <p class="lt ne trimfull">Rechnung anzeigen</p>

                                                    <div class="cl"></div>
                                                </li>
                                            </a>

                                        </ul>
                                    </datalist>
                                </div>
                            </div>

                        </div>

                        <div class="cl"></div>
                    </div>

                    <!-- PRODUCTS -->
                    <div class="products-outer">
                        <?php

                        // GET PRODUCT INFORMATION
                        $selProd = $c->prepare("
                                    SELECT * FROM customer_buys_products 
                                    WHERE bid = ?
                                ");
                        $selProd->bind_param('s', $s['oid']);
                        $selProd->execute();
                        $sPr_rr = $selProd->get_result();
                        $selProd->close();

                        if ($sPr_rr->rowCount() > 3) {

                            // GET PRODUCT INFORMATION
                            $selProd = $c->prepare("
                                        SELECT * FROM customer_buys_products, products, products_images 
                                        WHERE customer_buys_products.pid = products.id 
                                        AND products.id = products_images.pid 
                                        AND bid = ? 
                                        AND isgal = '1'
                                        LIMIT 3
                                    ");
                            $selProd->bind_param('s', $s['oid']);
                            $selProd->execute();
                            $sPr_r = $selProd->get_result();

                            while ($p = $sPr_r->fetch_assoc()) {

                        ?>

                                <div class="prod mshd-1">
                                    <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $imgurl . '/products/' . $p['url']; ?>">
                                </div>

                            <?php

                            } // END WHILE: PRODUCTS 

                            ?>

                            <div class="prod noprod">
                                <p>+ <?php echo $sPr_rr->rowCount() - 3; ?></p>
                            </div>

                            <?php

                        } else {

                            // GET PRODUCT INFORMATION
                            $selProd = $c->prepare("
                                        SELECT * FROM customer_buys_products, products, products_images 
                                        WHERE customer_buys_products.pid = products.id 
                                        AND products.id = products_images.pid 
                                        AND bid = ? 
                                        AND isgal = '1'
                                        LIMIT 3
                                    ");
                            $selProd->bind_param('s', $s['oid']);
                            $selProd->execute();
                            $sPr_r = $selProd->get_result();

                            while ($p = $sPr_r->fetch_assoc()) {

                            ?>

                                <div class="prod hd-shd">
                                    <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $imgurl . '/products/' . $p['url']; ?>">
                                </div>

                        <?php

                            } // END WHILE: PRODUCTS 
                        } // END IF

                        ?>
                    </div>

                    <!-- TOOLS -->
                    <div class="tools-outer mt24">

                        <div class="lt disfl fldirrow ph32">


                            <?php if ($s['status'] === 'got') { ?>
                                <div class="btn-outline delivery" style="border:1px solid orange;background:orange;">
                                    <p style="color:white;">NEU</p>
                                </div>
                            <?php } else if ($s['status'] === 'sent') { ?>
                                <div class="btn-outline delivery" style="border:1px solid grey;">
                                    <p style="color:grey;">Versandt</p>
                                </div>
                            <?php } else if ($s['status'] === 'done') { ?>
                                <div class="btn-outline delivery" style="border:1px solid green;">
                                    <p style="color:green;">Abgeschlossen</p>
                                </div>
                            <?php } else if ($s['status'] === 'canceled') { ?>
                                <div class="btn-outline delivery" style="border:1px solid red;background:red;">
                                    <p style="color:white;">Storniert</p>
                                </div>
                            <?php } ?>

                            <!-- PAYMENT MADE -->
                            <?php if ($s['status'] !== 'canceled') { ?>
                                <?php if ($s['paid'] === '1') { ?>
                                    <div class="btn-outline delivery" style="border:1px solid rgba(0,0,0,.24);">
                                        <p style="color:rgba(0,0,0,.24);">Als bezahlt markiert</p>
                                    </div>
                                <?php } else if ($s['paid'] === '2') { ?>
                                    <div class="btn-outline delivery" style="border:1px solid green;">
                                        <p style="color:green;">Bezahlt</p>
                                    </div>
                                <?php } else { ?>
                                    <div class="btn-outline delivery" style="border:1px solid orange;">
                                        <p style="color:orange;">Nicht bezahlt</p>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                        </div>

                        <div class="rt disfl fldirrow">
                            <div class="delivery btn-outline">
                                <p>
                                    <?php

                                    if ($s['delivery'] === 'combi') {
                                        echo 'Kombi-Versand';
                                    } else {
                                        echo 'Einzelversand';
                                    }

                                    ?>
                                </p>
                            </div>

                            <div class="btn-outline">
                                <p>EUR <?php echo number_format($s['price'], 2, ',', '.'); ?></p>
                            </div>
                        </div>

                        <div class="cl"></div>
                    </div>

                </div>

                <div class="cl"></div>
            </div>
        </content-card>

<?php

    } // END WHILE

} else {
    exit;
}


?>