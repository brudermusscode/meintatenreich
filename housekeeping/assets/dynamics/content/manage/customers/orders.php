<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $uid = htmlspecialchars($_REQUEST['id']);

    // CHECK IF ORDER EXISTS
    $sel = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
    $sel->bind_param('s', $uid);
    $sel->execute();
    $sel_r = $sel->get_result();
    $sel->close();

    if ($sel_r->rowCount() > 0) {

        // GET INFORMATION
        $us = $sel_r->fetch_assoc();

?>

        <wide-container style="padding-top:122px;margin-bottom:122px;" data-json='[{"id":"<?php echo $uid; ?>"}]'>

            <div class="head-text mb12">
                <p>Alle Bestellungen</p>
            </div>

            <!-- GENERAL -->
            <?php

            // GET ALL ORDERS & USER INFORMATION
            $selOrd = $pdo->prepare("SELECT * FROM customer_buys WHERE uid = ?");
            $selOrd->bind_param('s', $uid);
            $selOrd->execute();
            $selOrdR = $selOrd->get_result();

            if ($selOrdR->rowCount() < 1) {

            ?>

                <content-card class="mb24">
                    <div class="order hd-shd adjust">

                        <div style="padding:42px 42px;">
                            <p class="fw7 tac">Keine Bestellungen</p>
                        </div>

                    </div>
                </content-card>

                <?php

            } else {

                while ($so = $selOrdR->fetch_assoc()) {

                    $oid = $so['id'];

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

                                <div class="top" style="color:#5068A1;">
                                    <div class="name lt">
                                        <p class="trimfull">

                                            <?php

                                            // CHECK CUSTOMER NAME
                                            if (strlen($us['firstname']) > 0 && strlen($us['secondname']) > 0) {
                                                echo $us['firstname'] . ' ' . $us['secondname'];
                                            } else {
                                                echo $us['displayname'];
                                            }

                                            ?>

                                        </p>
                                    </div>
                                    <div class="type lt">
                                        <p>Bestellung</p>
                                    </div>
                                    <div class="top-right">

                                        <div class="rt status">
                                            <div data-element="admin-select" data-list-align="right" data-list-size="280" style="height:42px;width:42px;position:relative;overflow:hdden;" class="tran-all">
                                                <div class="outline disfl fldirrow" style="border:0;width:100%;height:100%;padding:0;margin:0;">
                                                    <p class="icon"><i class="material-icons md-24 lh42">more_vert</i></p>
                                                </div>

                                                <datalist class="tran-all-cubic">
                                                    <ul>
                                                        <li class="wic" data-action="manage:order" data-json='[{"id":"<?php echo $so['orderid']; ?>"}]'>
                                                            <p class="ic lt"><i class="material-icons md-18">build</i></p>
                                                            <p class="lt ne trimfull">Bestellung verwalten</p>

                                                            <div class="cl"></div>
                                                        </li>
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
                                    $selProd = $pdo->prepare("
                                        SELECT * FROM customer_buys_products 
                                        WHERE bid = ?
                                    ");
                                    $selProd->bind_param('s', $oid);
                                    $selProd->execute();
                                    $sPr_rr = $selProd->get_result();
                                    $selProd->close();

                                    if ($sPr_rr->rowCount() > 3) {

                                        // GET PRODUCT INFORMATION
                                        $selProd = $pdo->prepare("
                                            SELECT * FROM customer_buys_products, products, products_images 
                                            WHERE customer_buys_products.pid = products.id 
                                            AND products.id = products_images.pid 
                                            AND bid = ? 
                                            AND isgal = '1'
                                            LIMIT 3
                                        ");
                                        $selProd->bind_param('s', $oid);
                                        $selProd->execute();
                                        $sPr_r = $selProd->get_result();

                                        while ($p = $sPr_r->fetch_assoc()) {

                                    ?>

                                            <div class="prod mshd-1">
                                                <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $p['url']; ?>">
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
                                        $selProd = $pdo->prepare("
                                            SELECT * FROM customer_buys_products, products, products_images 
                                            WHERE customer_buys_products.pid = products.id 
                                            AND products.id = products_images.pid 
                                            AND bid = ? 
                                            AND isgal = '1'
                                            LIMIT 3
                                        ");
                                        $selProd->bind_param('s', $oid);
                                        $selProd->execute();
                                        $sPr_r = $selProd->get_result();

                                        while ($p = $sPr_r->fetch_assoc()) {

                                        ?>

                                            <div class="prod hd-shd">
                                                <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $p['url']; ?>">
                                            </div>

                                    <?php

                                        } // END WHILE: PRODUCTS 
                                    } // END IF

                                    ?>
                                </div>

                                <!-- TOOLS -->
                                <div class="tools-outer mt24 disfl fldirrow jstfycflend">

                                    <div class="delivery btn-outline">
                                        <p>
                                            <?php

                                            if ($so['delivery'] === 'combi') {
                                                echo 'Kombi-Versand';
                                            } else {
                                                echo 'Einzelversand';
                                            }

                                            ?>
                                        </p>
                                    </div>

                                    <div class="btn-outline">
                                        <p>EUR <?php echo number_format($so['price'], 2, ',', '.'); ?></p>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                            </div>

                            <div class="cl"></div>
                        </div>
                    </content-card>


            <?php

                } // END WHILE: ORDERS 

            } // END IF

            ?>

        </wide-container>

<?php

    } else {
        exit;
    }
} else {
    exit;
}

?>