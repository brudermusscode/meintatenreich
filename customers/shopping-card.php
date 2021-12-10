<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$loggedIn) {
    header('location: /');
}

$ptit = 'Warenkorb';
$pid = "scard";
$rgname = 'Warenkorb';

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">

            <div class="scard main-overflow-scroll">

                <div class="mt12 rd4 p8" style="background:#fff1da;">
                    <p class="fs14">Bitte lese unsere <a href="<?php echo $url["intern"]; ?>/disclaimer">Datenschutzerklärung</a>, alles zu <a href="<?php echo $url["intern"]; ?>/disclaimer#cookies">Cookies</a> und die von uns zur Verfügung gestellten Informationen zum im EU-Raum geltenden <a href="<?php echo $url["intern"]; ?>/sepa">SEPA-Lastschirftverfahren</a></p>
                </div>

                <div class="sc-inr">

                    <?php if (isset($_GET["pr"], $_GET["del"])) { ?>

                        <div class="">
                            <div class="rdcir" style="background:green;border-radius:50%;height:120px;width:120px;margin:0 auto;text-align:center;line-height:120px;">
                                <p style="font-size:4em;color:#fff;"><i class="icon-ok"></i></p>
                            </div>
                            <div class="tac">
                                <p class="mt42 lh24" style="font-size:1.4em;">Deine Bestellung über <strong style="font-weight:700;font-size:1.6em;"><?php echo $_GET['pr']; ?> €</strong> wurde erfolgreich entgegen genommen<br>und Du solltest bereits eine E-Mail zur Bestätigung erhalten haben!</p>
                            </div>

                            <?php if ($_GET['del'] === 'combi') { ?>
                                <div class="important red rd3 cf disfl fldirrow posrel mt38">
                                    <p class="icon"><i class="icon-attention-4"></i></p>
                                    <p>
                                        Durch den ausgewählten "Kombi-Versand" müssen wir die Versandkosten neu berechnen und werden diese<br><strong style="font-weight:700;">innerhalb der nächsten 24 Stunden</strong> per E-Mail an dich senden. Wir bitten um Dein Verständnis!
                                    </p>
                                </div>
                            <?php } else { ?>
                                <div class="important red rd3 cf disfl fldirrow posrel mt38">
                                    <p class="icon"><i class="icon-attention-4"></i></p>
                                    <p>
                                        Du hast die Möglichkeit, deine Bestellung binnen der nächsten 2 Stunden wieder zu stornieren. Danach ist sie voll verbindlich.
                                    </p>
                                </div>
                            <?php } ?>
                        </div>

                    <?php } else { ?>

                        <div class="w70 lt posrel">

                            <div class="title">
                                <p>Warenkorb</p>
                            </div>


                            <!-- ACTUAL CARD ITEMS -->
                            <div data-json='[{"pid":[<?php

                                                        // GET PRODUCT IDS
                                                        $getProducts = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ?");
                                                        $getProducts->execute([$my->id]);

                                                        $pNum = $getProducts->rowCount();
                                                        $counter = 0;

                                                        foreach ($getProducts->fetchAll() as $p) {

                                                            if (++$counter !== $pNum) {
                                                                $sppid = $p->pid;
                                                                echo "\"$sppid\",";
                                                            } else {
                                                                $sppid = $p->pid;
                                                                echo "\"$sppid\"";
                                                            }
                                                        }

                                                        ?>]}]' data-contain="product-id" class="card-outer">

                                <?php

                                // get whole product information
                                $getProducts = $pdo->prepare("
                                SELECT shopping_card.pid, products.name, products.price, products.mwstr, products.artnr, shopping_card.uid, products_images.url
                                FROM shopping_card, products, products_images 
                                WHERE shopping_card.pid = products.id 
                                AND products.id = products_images.pid 
                                AND products_images.isgal = '1' 
                                AND shopping_card.uid = ? 
                            ");
                                $getProducts->execute([$my->id]);

                                if ($getProducts->rowCount() < 1) {

                                ?>

                                    <div class="sc-none mshd-1 bgf rd3">
                                        <div class="p42 disfl fldircol alitc jstfycc">
                                            <p class="eye eyes2 mb12"></p>
                                            <p>Bisher nichts hinzugefügt!</p>
                                        </div>
                                    </div>

                                    <?php

                                } else {

                                    foreach ($getProducts->fetchAll() as $p) {


                                    ?>

                                        <div class="one mshd-1 bgf mb12 rd3 tran-all">
                                            <div class="o-inr">

                                                <div class="delete tran-all" data-action="delete-scard" data-json='[{"id":"<?php echo $p->pid; ?>"}]'>
                                                    <p><i class="icon-cancel"></i></p>
                                                </div>

                                                <div class="pr-image lt">
                                                    <div class="actual tran-all vishid opa0" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $p->url; ?>) center no-repeat;background-size:cover;">
                                                        <img src="<?php echo $url["img"]; ?>/products/<?php echo $p->url; ?>" class="opa0 vishid hw1" onload="fadeInVisOpaBg($(this).parent())">
                                                    </div>
                                                </div>

                                                <div class="pr-information lt">
                                                    <div class="pri-inr">
                                                        <div class="lt w78">
                                                            <div class="name">
                                                                <p class="trimfull"><?php echo $p->name; ?></p>
                                                            </div>
                                                            <div class="article-nr">
                                                                <p class="trimfull"><?php echo $p->artnr; ?></p>
                                                            </div>
                                                            <div class="mwstr">
                                                                <p>

                                                                    <?php

                                                                    if ($p->mwstr === '1') {
                                                                        echo 'Inkl. ' . $main["mwstr"] . ' % MwSt.';
                                                                    } else {
                                                                        echo 'Ohne MwSt.';
                                                                    }

                                                                    ?>

                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="rt">
                                                            <div class="price">
                                                                <p><?php echo number_format($p->price, 2, ',', '.') . ' €'; ?></p>
                                                            </div>
                                                        </div>

                                                        <div class="cl"></div>
                                                    </div>
                                                </div>

                                                <div class="cl"></div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <div class="rt w28">

                            <div data-react="checkout" class="checkout"></div>

                        </div>

                    <?php } ?>

                    <div class="cl"></div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    $(function() {
        getScardOverview();

        $('body').addClass('scard');
    });
</script>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>