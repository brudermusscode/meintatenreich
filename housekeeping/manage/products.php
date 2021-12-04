<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oopsie');
}

$ptit = 'Manage: Produkte';
$pid = "manage:products";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>

<main-content class="overview">

    <!-- MAIN HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>

    <!-- MC: CONTENT -->
    <div class="mc-main">

        <div class="wide mb42">

            <!-- CATEGORIES -->
            <div class="mm-heading">
                <p class="title lt">Produktkategorien</p>
                <div class="cl"></div>
            </div>

            <div class="mb42" data-react="manage:products,category,add">

                <?php

                $getProductsCategories = $pdo->prepare("SELECT * FROM products_categories ORDER BY id DESC");
                $getProductsCategories->execute();

                foreach ($getProductsCategories->fetchAll() as $s) {

                ?>

                    <content-card class="lt mr8 mb8" data-id="<?php echo $s->id; ?>" data-element="products:category">
                        <div class="normal-box adjust curpo" data-action="manage:products,category,edit" data-json='[{"id":"<?php echo $s->id; ?>"}]'>
                            <div class="ph24 lh36">
                                <p class="fw4" style="white-space:nowrap;"><?php echo $s->category_name; ?></p>
                            </div>
                        </div>
                    </content-card>

                <?php } ?>



                <content-card class="lt mr8 posrel" data-action="manage:products,category,add">
                    <div class="normal-box adjust">
                        <div class="ph24 lh36" style="height:36px;overflow:hidden;color:#A247C0;cursor:pointer;white-space:nowrap;">
                            <p class="lt mr8"><i class="material-icons md-18 lh36">add</i></p>
                            <p class="fw5 lt">Hinzufügen</p>

                            <div class="cl"></div>
                        </div>
                    </div>
                </content-card>

                <div class="cl"></div>
            </div>

            <!-- ALL PRODUCTS-->
            <div class="mm-heading">
                <p class="title lt lh42">Alle Produkte</p>
                <div class="tools lt ml32">
                    <div data-element="admin-select" data-action="manage:filter" data-page="products" data-list-size="212" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                        <div class="outline disfl fldirrow">
                            <p class="text">Filtern</p>
                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                        </div>

                        <datalist class="tran-all-cubic">
                            <ul>
                                <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                                <li class="trimfull" data-json='[{"order":"available"}]'>Verfügbare</li>
                                <li class="trimfull" data-json='[{"order":"unavailable"}]'>Nicht verfügbare</li>
                                <li class="trimfull" data-json='[{"order":"reserved"}]'>Reservierte</li>
                                <li class="trimfull" data-json='[{"order":"priceup"}]'>Preis aufwärts</li>
                                <li class="trimfull" data-json='[{"order":"pricedown"}]'>Preis abwärts</li>
                            </ul>
                        </datalist>
                    </div>
                </div>

                <div class="rt">
                    <div class="mshd-1" style="color:#A247C0;border-radius:50px;background:white;cursor:pointer;padding:0 18px;" data-action="manage:products,add">
                        <p class="lt mr12"><i class="material-icons md-24 lh42">add</i></p>
                        <p class="lt lh42">Produkt hinzufügen</p>

                        <div class="cl"></div>
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
                $sel = $pdo->prepare("
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products_images.isgal = '1'
                    ORDER BY products.id DESC
                ");
                $sel->execute();

                if ($sel->rowCount() < 1) {

                ?>

                    <content-card class="mb24">
                        <div class="order hd-shd adjust">
                            <div style="padding:82px 42px;">
                                <p class="tac">Keine Produkte hinzugefügt</p>
                            </div>

                        </div>
                    </content-card>

                <?php

                }

                foreach ($sel->fetchAll() as $s) {

                    $id = $s->pid;

                    $res = false;
                    $selres = $pdo->prepare("SELECT * FROM products_reserved WHERE pid = ? AND active = 1");
                    $selres->execute([$id]);

                    if ($selres->rowCount() > 0) {
                        $res = true;
                    }

                ?>

                    <content-card class="mb24 lt tripple">
                        <div class="products hd-shd adjust">

                            <div class="image">
                                <div class="image-outer">
                                    <div class="actual">
                                        <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $s->url; ?>">
                                    </div>
                                </div>

                                <div class="overlay">
                                    <div class="top">
                                        <div class="rt">
                                            <div data-element="admin-select" data-list-size="284" data-list-align="right" class="icon tran-all posrel">
                                                <p class="ac-ic">
                                                    <i class="material-icons md-24">more_vert</i>
                                                </p>

                                                <datalist class="tran-all-cubic">
                                                    <ul>
                                                        <li class="wic" data-action="manage:products,edit" data-json='[{"id":"<?php echo $id; ?>"}]'>
                                                            <p class="ic lt"><i class="material-icons md-18">edit</i></p>
                                                            <p class="lt ne trimfull">Produkt bearbeiten</p>

                                                            <div class="cl"></div>
                                                        </li>

                                                        <li class="wic" data-action='manage:products,toggle' data-json='[{"id":"<?php echo $id; ?>"}]'>
                                                            <p class="ic lt"><i class="material-icons md-18">visibility_off</i></p>
                                                            <p class="lt ne trimfull">Produkt deaktivieren</p>

                                                            <div class="cl"></div>
                                                        </li>

                                                        <div class="dist" style="width:100%;margin:12px 0;border-bottom:1px solid rgba(0,0,0,.04);"></div>

                                                        <li class="wic" data-action='manage:products,delete' data-json='[{"id":"<?php echo $id; ?>"}]'>
                                                            <p class="ic lt"><i class="material-icons md-18">clear</i></p>
                                                            <p class="lt ne trimfull">Löschen</p>

                                                            <div class="cl"></div>
                                                        </li>
                                                    </ul>
                                                </datalist>
                                            </div>
                                        </div>

                                        <div class="cl"></div>
                                    </div>

                                    <div class="bottom">
                                        <div class="price rt">
                                            <p>EUR <?php echo number_format($s->price, 2, ',', '.'); ?></p>
                                        </div>

                                        <div class="cl"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="inr-content">
                                <div class="name">
                                    <p class="trimfull"><?php echo $s->name; ?></p>
                                </div>

                                <div class="artnr">
                                    <p class="ttup tac fw4 lt mr8">
                                        <i class="material-icons md-18 lh32">bookmark</i>
                                    </p>
                                    <p class="ttup tac fw4 lh32 lt"><?php echo $s->artnr; ?></p>

                                    <div class="cl"></div>
                                </div>

                                <div class="av rt">

                                    <?php if ($res === true) { ?>

                                        <div class="av-outer o">
                                            <p class="ttup">Reserviert</p>
                                        </div>

                                    <?php } else { ?>

                                        <?php if ($s->available === '1') { ?>
                                            <div class="av-outer g">
                                                <p class="ttup">Verfügbar</p>
                                            </div>
                                        <?php } else { ?>
                                            <div class="av-outer r">
                                                <p class="ttup">Nicht verfügbar</p>
                                            </div>
                                        <?php } ?>

                                    <?php } ?>
                                </div>

                                <div class="cl"></div>
                            </div>

                        </div>
                    </content-card>


                <?php } ?>

                <div class="cl"></div>

            </div>

        </div>

    </div>
</main-content>

<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>