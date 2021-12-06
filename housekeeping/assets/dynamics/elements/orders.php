<?php

if (isset($elementInclude) && $admin->isAdmin()) {

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
                            if (strlen($elementInclude->firstname) > 0 && strlen($elementInclude->secondname) > 0) {
                                echo $elementInclude->firstname . ' ' . $elementInclude->secondname;
                            } else {
                                echo $elementInclude->displayname;
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
                                        <li class="wic" data-action="manage:order" data-json='[{"id":"<?php echo $elementInclude->orderid; ?>"}]'>
                                            <p class="ic lt"><i class="material-icons md-18">build</i></p>
                                            <p class="lt ne trimfull">Bestellung verwalten</p>

                                            <div class="cl"></div>
                                        </li>

                                        <li class="wic" data-action="manage:customers,orders" data-json='[{"id":"<?php echo $elementInclude->uid; ?>"}]'>
                                            <p class="ic lt"><i class="material-icons md-18">widgets</i></p>
                                            <p class="lt ne trimfull">Alle Bestellungen des Kunden</p>

                                            <div class="cl"></div>
                                        </li>

                                        <a href="/bills/<?php echo $elementInclude->pdfid; ?>" target="_blank" style="color:rgb(80, 104, 161);">
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
                    $getOrdersProducts = $pdo->prepare("
                        SELECT * 
                        FROM customer_buys_products, products, products_images 
                        WHERE customer_buys_products.pid = products.id 
                        AND products.id = products_images.pid 
                        AND isgal = '1' 
                        AND bid = ?
                        ORDER BY customer_buys_products.id
                        DESC
                    ");
                    $getOrdersProducts->execute([$elementInclude->oid]);

                    $count = 0;
                    foreach ($getOrdersProducts->fetchAll() as $p) {

                    ?>

                        <div class="prod mshd-1">
                            <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $p->url; ?>">
                        </div>

                        <?php

                        if ($count == 3 - 1) {

                            break;
                        } else {
                            $count++;
                        }
                    }

                    if ($getOrdersProducts->rowCount() > 3) {

                        ?>

                        <div class="prod noprod">
                            <p>+ <?php echo $getOrdersProducts->rowCount() - 3; ?></p>
                        </div>


                    <?php } ?>

                </div>

                <!-- TOOLS -->
                <div class="tools-outer mt24">

                    <div class="lt disfl fldirrow ph32">


                        <?php if ($elementInclude->status === 'got') { ?>
                            <div class="btn-outline delivery" style="border:1px solid orange;background:orange;">
                                <p style="color:white;">NEU</p>
                            </div>
                        <?php } else if ($elementInclude->status === 'sent') { ?>
                            <div class="btn-outline delivery" style="border:1px solid grey;">
                                <p style="color:grey;">Versandt</p>
                            </div>
                        <?php } else if ($elementInclude->status === 'done') { ?>
                            <div class="btn-outline delivery" style="border:1px solid green;">
                                <p style="color:green;">Abgeschlossen</p>
                            </div>
                        <?php } else if ($elementInclude->status === 'canceled') { ?>
                            <div class="btn-outline delivery" style="border:1px solid red;background:red;">
                                <p style="color:white;">Storniert</p>
                            </div>
                        <?php } ?>

                        <!-- PAYMENT MADE -->
                        <?php if ($elementInclude->status !== 'canceled') { ?>
                            <?php if ($elementInclude->paid === '1') { ?>
                                <div class="btn-outline delivery" style="border:1px solid rgba(0,0,0,.24);">
                                    <p style="color:rgba(0,0,0,.24);">Als bezahlt markiert</p>
                                </div>
                            <?php } else if ($elementInclude->paid === '2') { ?>
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

                                if ($elementInclude->delivery === 'combi') {
                                    echo 'Kombi-Versand';
                                } else {
                                    echo 'Einzelversand';
                                }

                                ?>
                            </p>
                        </div>

                        <div class="btn-outline">
                            <p>EUR <?php echo number_format($elementInclude->price, 2, ',', '.'); ?></p>
                        </div>
                    </div>

                    <div class="cl"></div>
                </div>

            </div>

            <div class="cl"></div>
        </div>
    </content-card>

<?php

} else {
    exit;
}

?>