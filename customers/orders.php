<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Übersicht";
$pid = "profile";
$rgname = 'Konto';


if (!$loggedIn) {
    header('location: ../oops');
}

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">


            <?php include_once $sroot . "/assets/templates/customers/menu.php"; ?>


            <div class="main-overflow-scroll rt mysection">

                <?php

                // get orders
                $getOrders = $pdo->prepare("SELECT * FROM customer_buys WHERE uid = ? ORDER BY timestamp DESC");
                $getOrders->execute([$my->id]);

                if ($getOrders->rowCount() < 1) {

                ?>

                    <div class="tac">
                        <div class="eyes2" style="height:68px;width:120px;margin:42px auto 12px;"></div>
                        <p>Keine Bestellungen aufgegeben</p>

                    </div>

                    <?php

                } else {

                    foreach ($getOrders->fetchAll() as $o) {

                        $bprice = $o->price;
                        $bdprice = $o->price_delivery;

                        $prids = [];
                        $price = [];

                        // get order products
                        $getOrdersProducts = $pdo->prepare("
                            SELECT customer_buys_products.pid, products.price 
                            FROM customer_buys_products, products 
                            WHERE customer_buys_products.pid = products.id 
                            AND bid = ?
                        ");
                        $getOrdersProducts->execute([$o->id]);

                        foreach ($getOrdersProducts->fetchAll() as $p) {

                            $prids[] = $p->pid;
                            $price[] =  $p->price;
                        }

                        // convert timestamp
                        $timeAgoObject = new convertToAgo;
                        $ts = $o->timestamp;
                        $convertedTime = ($timeAgoObject->convert_datetime($ts));
                        $when = ($timeAgoObject->makeAgo($convertedTime));

                        // get bill
                        $getOrderPDF = $pdo->prepare("SELECT * FROM customer_buys_pdf WHERE bid = ?");
                        $getOrderPDF->execute([$o->id]);
                        $opdf = $getOrderPDF->fetch();

                    ?>

                        <order>

                            <div class="label mb12">
                                <p>
                                    <i class="icon-calendar"></i> &nbsp;
                                    Bestellung <?php echo $when; ?> <span style="color:rgba(0,0,0,.12);">(<?php echo $o->timestamp; ?>)</span>
                                    &nbsp; <i class="icon-down-open"></i>
                                </p>
                            </div>

                            <div class="overview">
                                <div class="tools lt">

                                    <div class="mb12">
                                        <p class="c9">
                                            <?php if ($o->status === 'done') { ?>
                                                <i class="icon-ok"></i> &nbsp;
                                            <?php } else if ($o->status === 'canceled') { ?>
                                                <i class="icon-cancel"></i> &nbsp;
                                            <?php } else { ?>
                                                <i class="icon-spin1 animate-spin"></i> &nbsp;
                                            <?php } ?>
                                            Status
                                        </p>
                                    </div>

                                    <div style="border-left:2px solid rgba(0,0,0,.12);margin-left:6px;padding-left:24px;">
                                        <div class="status 
                                                <?php

                                                switch ($o->status) {
                                                    case 'got':
                                                        echo 'got';
                                                        break;
                                                    case 'sent':
                                                        echo 'sent';
                                                        break;
                                                    case 'done':
                                                        echo 'done';
                                                        break;
                                                    case 'canceled':
                                                        echo 'canc';
                                                }

                                                ?>
                                        ">
                                            <p>
                                                <?php

                                                switch ($o->status) {
                                                    case 'got':
                                                        echo 'Bestellung ist eingegangen';
                                                        break;
                                                    case 'sent':
                                                        echo 'Bestellung wurde versandt';
                                                        break;
                                                    case 'done':
                                                        echo 'Bestellung ist eingetroffen';
                                                        break;
                                                    case 'canceled':
                                                        echo 'Bestellung wurde storniert';
                                                }

                                                ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt12">

                                        <!-- PRODUCTS GOT -->
                                        <div class="lt">

                                            <?php if ($o->status === 'got' && $o->cancability !== '0') { ?>

                                                <button type="button" class="hellofresh hlf-pink-s normal rd3 mshd-1" data-action="cancel-order" data-json='[{"id":"<?php echo $o->id; ?>"}]'>
                                                    Stornieren
                                                </button>

                                            <?php

                                            } else if ($o->status === 'sent') {

                                            ?>

                                                <button type="button" class="hellofresh hlf-green-s normal rd3 mshd-1" data-action="order:received,confirm" data-json='[{"id":"<?php echo $o->id; ?>"}]'>
                                                    Ware erhalten bestätigen
                                                </button>

                                            <?php

                                            } else if ($o->status === 'done' && $o->cancability === '0') {

                                            ?>

                                                <button type="button" class="hellofresh hlf-green-s normal rd3 mshd-1" disabled="disabled">
                                                    Ware erhalten bestätigt
                                                </button>

                                            <?php } ?>

                                        </div>

                                        <!-- PAYMENT MADE -->
                                        <?php if ($o->status !== 'canceled') { ?>
                                            <div class="ml4 lt">
                                                <?php if ($o->paid === '0') { ?>
                                                    <button type="button" class="hellofresh hlf-blue-s normal rd3 mshd-1" data-action="manage:order,pay" data-json='[{"id":"<?php echo $o->id; ?>"}]'>
                                                        Als bezahlt markieren
                                                    </button>
                                                <?php } else if ($o->paid === '1') { ?>
                                                    <button type="button" class="hellofresh hlf-white-s normal rd3 mshd-1" disabled="disabled">
                                                        Als bezahlt markiert
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button" class="hellofresh hlf-white-s normal rd3 mshd-1" disabled="disabled">
                                                        Bezahlung bestätigt
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>

                                        <div class="ml4 lt">
                                            <a href="/bills/<?php echo $opdf->id; ?>" target="_blank" style="color:rgb(80, 104, 161);">
                                                <button type="button" class="hellofresh hlf-pink-s normal rd3 mshd-1">
                                                    Rechnung
                                                </button>
                                            </a>
                                        </div>

                                        <div class="cl"></div>
                                    </div>

                                </div>

                                <div class="rt tar mt12">
                                    <p class="ttup">gesamt (inkl. Versand)</p>
                                    <p style="color:#e62e04;font-size:2em;line-height:1.2;"><?php echo number_format($bprice + $bdprice, 2, ',', '.') . ' €'; ?></p>
                                </div>

                                <div class="cl"></div>
                            </div>

                            <?php

                            foreach ($prids as $pid) {

                                $getProductsImages = $pdo->prepare("SELECT * FROM products, products_images WHERE products.id = products_images.pid AND products.id = ? AND products_images.isgal = '1'");
                                $getProductsImages->execute([$pid]);

                                foreach ($getProductsImages->fetchAll() as $pi) {

                            ?>

                                    <order-card>
                                        <div class="oc-inr">

                                            <div class="image lt">
                                                <div class="actual tran-all opa0 vishid" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $pi->url; ?>) center no-repeat;background-size:cover;">
                                                    <img class="tran-all" onload="fadeInVisOpaBg($(this).parent())" src="<?php echo $url["img"]; ?>/products/<?php echo $pi->url; ?>">
                                                </div>
                                            </div>

                                            <div class="info lt ml12">
                                                <a href="/product/<?php echo $pi->artnr; ?>">
                                                    <p class="name trimfull"><?php echo $pi->name; ?></p>
                                                </a>
                                                <p class="item-id"><?php echo $pi->artnr; ?></p>

                                                <div class="price">
                                                    <p>Einzelpreis</p>
                                                    <p><?php echo number_format($pi->price, 2, ',', '.') . ' €'; ?></p>
                                                </div>
                                            </div>

                                            <div class="cl"></div>

                                        </div>
                                    </order-card>

                            <?php
                                }
                            }

                            ?>

                        </order>

                <?php

                    } // END WHILE
                }

                ?>



                <!-- RECOMMENDED PRODUCTS -->
                <div style="padding-top:32px;margin-bottom:12px;margin-top:42px;border-top:2px dashed rgba(0,0,0,.12);">
                    <p style="font-size:1.4em;color:#A58862;" class="tac">Vorschläge</p>
                </div>

                <div class="mb24">

                    <?php

                    $getRecommendedProducts = $pdo->prepare("
                        SELECT products.*, products_images.url 
                        FROM products, products_images 
                        WHERE products.id = products_images.pid 
                        AND products_images.isgal = '1' 
                        AND products.available = '1' 
                        ORDER BY RAND()  
                        LIMIT 3
                    ");
                    $getRecommendedProducts->execute();

                    foreach ($getRecommendedProducts->fetchAll() as $rp) {

                    ?>

                        <a href="/product/<?php echo $rp->artnr; ?>" class="tran-all">
                            <product-card class="mshd-1 tri">
                                <div class="pr-inr">
                                    <div class="pr-img-outer">
                                        <div class="img vishid opa0 tran-all" style="background:url(<?php echo $url['img']; ?>/products/<?php echo $rp->url; ?>) center no-repeat;background-size:cover;">
                                            <img class="vishid opa0 hw1 tran-all" onload="fadeInVisOpaBg($(this).parent())" src="<?php echo $url['img']; ?>/products/<?php echo $rp->url; ?>">
                                        </div>
                                    </div>

                                    <div class="pr-info">
                                        <p class="pr-name trimfull">
                                            <?php echo $rp->name; ?>
                                        </p>
                                        <p class="pr-price">
                                            <?php echo number_format($rp->price, 2, ',', '.') . ' €'; ?>
                                        </p>
                                    </div>
                                </div>
                            </product-card>
                        </a>

                    <?php }
                    ?>


                </div>

            </div>

            <div class="cl"></div>
        </div>
    </div>
</div>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>