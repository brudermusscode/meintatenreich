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

            <color-loader class="almid-h mt24 mb42">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-react="manage:filter"></div>

        </div>
    </div>
</main-content>

<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>