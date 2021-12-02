<?php

require_once "../../mysql/_.session.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oopsie');
    }
} else {
    header('location: /oopsie');
}

$ptit = 'Manage: Bestellungen';
$pid = "manage:orders";

include_once "../assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once "../assets/templates/menu.php"; ?>

<script>
    $(function() {

        $.fn.circularProgress = function() {
            var DEFAULTS = {
                backgroundColor: '#b3cef6',
                progressColor: '#4b86db',
                percent: 75,
                duration: 400
            };

            $(this).each(function() {
                var $target = $(this);

                var opts = {
                    backgroundColor: $target.data('color') ? $target.data('color').split(',')[0] : DEFAULTS.backgroundColor,
                    progressColor: $target.data('color') ? $target.data('color').split(',')[1] : DEFAULTS.progressColor,
                    percent: $target.data('percent') ? $target.data('percent') : DEFAULTS.percent,
                    duration: $target.data('duration') ? $target.data('duration') : DEFAULTS.duration
                };
                // console.log(opts);

                $target.append('<div class="background"></div><div class="rotate"></div><div class="left"></div><div class="right"></div><div class=""><span>' + opts.percent + '%</span></div>');

                $target.find('.background').css('background-color', opts.backgroundColor);
                $target.find('.left').css('background-color', opts.backgroundColor);
                $target.find('.rotate').css('background-color', opts.progressColor);
                $target.find('.right').css('background-color', opts.progressColor);

                var $rotate = $target.find('.rotate');
                setTimeout(function() {
                    $rotate.css({
                        'transition': 'transform ' + opts.duration + 'ms cubic-bezier(.1,.82,.25,1)',
                        'transform': 'rotate(' + opts.percent * 3.6 + 'deg)'
                    });
                }, 1);

                if (opts.percent > 50) {
                    var animationRight = 'toggle ' + (opts.duration / opts.percent * 50) + 'ms cubic-bezier(.1,.82,.25,1)';
                    var animationLeft = 'toggle ' + (opts.duration / opts.percent * 50) + 'ms cubic-bezier(.1,.82,.25,1)';
                    $target.find('.right').css({
                        animation: animationRight,
                        opacity: 1
                    });
                    $target.find('.left').css({
                        animation: animationLeft,
                        opacity: 0
                    });
                }
            });
        }

        $(".progress-bar").circularProgress();

    });
</script>

<main-content>

    <!-- MC: HEADER -->
    <?php include_once "../assets/templates/header.php"; ?>

    <!-- MC: CONTENT -->
    <div class="mc-main">
        <div class="wide">

            <div class="mm-heading" data-closeout="manage:filter">
                <p class="title lt lh42">Bestellungen</p>
                <div class="tools lt ml32">
                    <div data-element="admin-select" data-action="manage:filter" data-page="orders" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                        <div class="outline disfl fldirrow">
                            <p class="text">Filtern</p>
                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                        </div>

                        <datalist class="tran-all-cubic">
                            <ul>
                                <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                                <li class="trimfull" data-json='[{"order":"got"}]'>Neue Bestellungen</li>
                                <li class="trimfull" data-json='[{"order":"sent"}]'>Versandte</li>
                                <li class="trimfull" data-json='[{"order":"done"}]'>Abgeschlossene</li>
                                <li class="trimfull" data-json='[{"order":"canceled"}]'>Stornierte</li>
                                <li class="trimfull" data-json='[{"order":"unpaid"}]'>Nicht bezahlte</li>
                                <li class="trimfull" data-json='[{"order":"paidmarked"}]'>Als bezahlt markierte</li>
                                <li class="trimfull" data-json='[{"order":"paid"}]'>Bezahlte</li>
                            </ul>
                        </datalist>
                    </div>
                </div>

                <div class="cl"></div>
            </div>

            <!-- LOADER -->
            <color-loader class="almid-h mt24 mb42 disn">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-react="manage:filter">

                <?php

                // GET ALL ORDERS & USER INFORMATION
                $sel = $c->prepare("
                            SELECT *, customer_buys.id AS oid 
                            FROM customer_buys, customer 
                            WHERE customer_buys.uid = customer.id 
                            ORDER BY customer_buys.id
                            DESC
                        ");
                $sel->execute();
                $sel_r = $sel->get_result();
                $sel->close();

                if ($sel_r->rowCount() < 1) {

                ?>

                    <content-card class="mb24">
                        <div class="order hd-shd adjust">
                            <div style="padding:82px 42px;">
                                <p class="tac">Keine Bestellungen verfügbar</p>
                            </div>

                        </div>
                    </content-card>

                <?php

                } // END IF EMPTY

                while ($s = $sel_r->fetch_assoc()) {

                    $id = $s['oid'];

                    // GET BILL PDF ID
                    $sel = $c->prepare("
                                SELECT * FROM customer_buys_pdf
                                WHERE bid = ?
                            ");
                    $sel->bind_param('s', $id);
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
                                                <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $p['url']; ?>">
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


                <?php } // END WHILE: ORDERS 
                ?>

            </div>

        </div>

    </div>
</main-content>