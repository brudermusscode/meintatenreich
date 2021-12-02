<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $id = $_REQUEST['id'];

    // SELECT PRODUCT
    $sel = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();

    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // GET PRODUCT INFO
        $s = $sr->fetch_assoc();

        // GET PRODUCT DESCRIPTION
        $sel = $pdo->prepare("
                SELECT * 
                FROM products_desc
                WHERE pid = ?
            ");
        $sel->bind_param('s', $id);
        $sel->execute();
        $sel_r = $sel->get_result();
        $sdesc = $sel_r->fetch_assoc();
        $desc = $sdesc['text'];

        // GET CURRENT CATEGORY
        $selCatCur = $pdo->prepare("SELECT * FROM products_categories WHERE id = ?");
        $selCatCur->bind_param('s', $s['cid']);
        $selCatCur->execute();
        $srr = $selCatCur->get_result();
        $selCatCur->close();

        $catCur = $srr->fetch_assoc();

?>



        <wide-container style="padding-top:62px;" data-json='[{"id":"<?php echo $id; ?>"}]'>


            <!-- PICTURES -->
            <div class="head-text mb12">
                <p>Bilder</p>
            </div>

            <div class="product-overview" data-action="manage:products,edit,addImage,gallery" data-react="manage:products,edit,addImage,show">

                <?php

                $sel = $pdo->prepare("
                    SELECT * 
                    FROM products_images
                    WHERE pid = ?
                    ORDER BY timestamp
                    DESC
                ");
                $sel->bind_param('s', $id);
                $sel->execute();
                $sel_r = $sel->get_result();

                while ($prdimg = $sel_r->fetch_assoc()) {

                ?>

                    <div class="item lt <?php if ($prdimg['isgal'] === '1') {
                                            echo 'gal';
                                        } ?>" data-json='[{"id":"<?php echo $prdimg['id']; ?>"}]'>
                        <div class="actual-image mshd-1 tran-all-cubic">
                            <img onload="fadeIn(this)" class="vishid opa0" src="<?php echo $url["img"] . '/products/' . $prdimg['url']; ?>">
                        </div>
                    </div>

                <?php

                }
                $sel->close(); // END WHILE PRD

                ?>


                <div class="item add-new lt" data-action="manage:products,edit,addImage">
                    <div class="actual-image mshd-1 posrel tran-all-cubic">
                        <div class="almid posabs tac" style="color:#5068A1;">
                            <p class="mb12"><i class="material-icons md-36">library_add</i></p>
                            <p style="font-size:1em;" class="fw6">Hinzufügen</p>
                        </div>
                    </div>
                </div>

                <div class="vishid opa0 hw1">
                    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                    <input id="image-penetration" type="file" name="pictures" multiple accept="image/*" data-react="manage:products,edit,addImage" />
                </div>

                <div class="cl"></div>

            </div>


            <div class="divide">
                <p class="mshd-1">
                    <i class="material-icons md-42">keyboard_arrow_down</i>
                </p>
            </div>


            <!-- GENERAL -->
            <div class="head-text mb12">
                <p>Allgemein</p>
            </div>

            <content-card class="mb42 posrel">
                <div class="mshd-1 normal-box">
                    <div style="padding:32px 42px;">

                        <form data-form="manage:products,edit">

                            <?php

                            // CHECK GALLERY IMAGE
                            $selGal = $pdo->prepare("SELECT * FROM products_images WHERE isgal = '1' AND pid = ?");
                            $selGal->bind_param('s', $id);
                            $selGal->execute();
                            $selGal_r = $selGal->get_result();
                            $selGal->close();
                            $sg = $selGal_r->fetch_assoc();

                            ?>

                            <input type="hidden" name="gallery" data-react="manage:products,edit,addImage,gallery" value="<?php echo $sg['id']; ?>">

                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Titel des Produktes</p>
                            </div>

                            <div class="input tran-all-cubic mb42">
                                <div class="input-outer">
                                    <input type="text" autocomplete="off" name="name" placeholder="Gebe einen Titel für das Produkt ein..." value="<?php echo $s['name']; ?>" class="tran-all">
                                </div>
                            </div>


                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Beschreibung</p>
                            </div>

                            <div class="textarea mb42">
                                <div class="textarea-outer">
                                    <textarea name="desc" placeholder="Was möchtest du dem Kunden mitteilen?" class="tran-all"><?php echo $desc; ?></textarea>
                                </div>
                            </div>


                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Preis (inkl. MwSt., wenn inklusive)</p>
                            </div>

                            <div class="input tran-all-cubic mb62">
                                <div class="input-outer">
                                    <div style="color:green;right:18px;padding-left:12px;line-height:32px;top:5px;font-size:1.2em;border-left:1px solid rgba(0,0,0,.12);" class="fw6 posabs">
                                        <p>€</p>
                                    </div>
                                    <input type="text" autocomplete="off" name="price" placeholder="Preis in EURO..." class="tran-all" value="<?php echo number_format($s['price'], 2, ',', '.'); ?>" style="padding-right:62px;width:calc(100% - 32px - 62px);">
                                </div>
                            </div>


                            <style>
                                .boolean-great {}

                                .boolean-great .outer {
                                    background: rgba(0, 0, 0, .24);
                                    border-radius: 100px;
                                    width: 60px;
                                    height: 32px;
                                    position: relative;
                                    cursor: pointer;
                                }

                                .boolean-great.on .outer {
                                    background: rgba(16, 155, 27, 0.24);
                                }

                                .boolean-great .outer .actual {
                                    height: calc(100% - 4px);
                                    width: 28px;
                                    background: white;
                                    border-radius: 50%;
                                    position: absolute;
                                    top: 2px;
                                    left: 2px;
                                }

                                .boolean-great.on .outer .actual {
                                    left: calc(100% - 2px);
                                    transform: translateX(-100%);
                                }

                                .boolean-input {
                                    width: 100%;
                                }

                                .boolean-input .desc-bool {
                                    position: relative;
                                }

                                .boolean-input .desc-bool .text {
                                    color: #999;
                                    width: calc(100% - 84px);
                                }

                                .boolean-input .desc-bool .bool {
                                    width: 60px;
                                }
                            </style>


                            <!-- CATEGORY -->
                            <div class="boolean-input mt32">

                                <div class="fw6 mb12">
                                    <p style="color:#5068A1;">Produktkategorie</p>
                                </div>

                                <div class="lt">

                                    <div data-element="admin-select" data-input="true" data-list-size="312" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                                        <div class="outline disfl fldirrow">
                                            <p class="text"><?php echo $catCur['name']; ?></p>
                                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                                        </div>

                                        <datalist class="tran-all-cubic">
                                            <ul>
                                                <?php

                                                $selCat = $pdo->prepare("SELECT * FROM products_categories ORDER BY id DESC");
                                                $selCat->execute();
                                                $selCat_r = $selCat->get_result();

                                                while ($cat = $selCat_r->fetch_assoc()) {

                                                ?>
                                                    <li class="trimfull" data-json='[{"id":"<?php echo $cat['id']; ?>"}]'>
                                                        <?php echo $cat['name']; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                            <input type="hidden" name="cid" value="<?php echo $catCur['id']; ?>">
                                        </datalist>
                                    </div>
                                </div>

                                <div class="cl"></div>
                            </div>


                            <!-- MWSTR -->
                            <div class="boolean-input mt42">

                                <div class="fw6 mb8">
                                    <p style="color:#5068A1;">Mehrwert-Steuer</p>
                                </div>

                                <div class="desc-bool">

                                    <p class="lt text">Sofern das Produkt eine Mehrwert-Steuer enthält, kann diese Option aktiviert werden. Die derzeitige Steuer beträgt <strong><?php echo $mwstr; ?> %</strong>.</p>

                                    <div class="bool rt">
                                        <div class="boolean-great <?php if ($s['mwstr'] === '1') {
                                                                        echo 'on';
                                                                    } ?>" data-element="boolean-great">
                                            <div class="outer tran-all">
                                                <div class="actual mshd-1 tran-all-cubic">
                                                    <div class="booler"></div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="mwstr" value="<?php echo $s['mwstr']; ?>">
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                            </div>

                            <!-- ACTIVE -->
                            <div class="boolean-input mt32">

                                <div class="fw6 mb8">
                                    <p style="color:#5068A1;">Verfügbarkeit</p>
                                </div>

                                <div class="desc-bool">

                                    <p class="lt text">Bei Ausverkauf des Prduktes wird diese Option automatisch deaktiviert. Sollte das Produkt aus dem Programm genommen werden, kann sie manuell ausgeschaltet werden.</p>

                                    <div class="bool rt">
                                        <div class="boolean-great <?php if ($s['available'] === '1') {
                                                                        echo 'on';
                                                                    } ?>" data-element="boolean-great">
                                            <div class="outer tran-all">
                                                <div class="actual mshd-1 tran-all-cubic">
                                                    <div class="booler"></div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="available" value="<?php echo $s['available']; ?>">
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                            </div>


                            <div data-action="manage:products,edit,save" class="btn-outline rt mt32" style="border-color:#AC49BD;color:#AC49BD;">
                                <p>Speichern</p>
                            </div>

                        </form>

                        <div class="cl"></div>
                    </div>
                </div>
            </content-card>

        </wide-container>


<?php

    } else {
        exit('1');
    }
} else {
    exit;
}

?>