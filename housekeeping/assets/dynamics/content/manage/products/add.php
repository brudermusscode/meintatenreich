<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if ($admin->isAdmin()) {

?>

    <wide-container style="padding-top:62px;" data-json='[{"id":"<?php echo $id; ?>"}]'>


        <!-- PICTURES -->
        <div class="head-text mb12">
            <p>Bilder</p>
        </div>

        <div class="product-overview mb42 posrel" data-react="manage:products,add,addImage,show" data-action="manage:products,add,addImage,gallery">

            <div class="vishid opa0 hw1">
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                <input id="upload-new-images" type="file" name="pictures" multiple accept="image/*" data-react="manage:products,add,addImage" />
            </div>

            <div class="item add-new lt" data-action="manage:products,add,addImage">
                <div class="actual-image mshd-1 posrel">
                    <div class="almid posabs tac" style="color:#5068A1;">
                        <p class="mb12"><i class="material-icons md-36">library_add</i></p>
                        <p style="font-size:1em;" class="fw6">Hinzufügen</p>
                    </div>
                </div>
            </div>

            <div class="cl"></div>

            <div class="mshd-4 tran-all-cubic" style="opacity:0;visibility:hidden;position:absolute;bottom:-42px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.92);border-radius:6px;" data-react="manage:products,add,addImage,gallery,info">
                <div style="padding:12px;color:white;font-weight:400;text-align:center;">
                    <p>Klicke auf ein Bild, um es als Hauptbild zu setzen!</p>
                </div>
            </div>

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

                    <form data-form="manage:products,add">

                        <input data-react="manage:products,add,addImage,gallery" type="hidden" name="gallery" value>

                        <div class="fw6 mb12">
                            <p style="color:#5068A1;">Titel des Produktes</p>
                        </div>

                        <div class="input tran-all-cubic mb42">
                            <div class="input-outer">
                                <input type="text" autocomplete="off" name="name" placeholder="Gebe einen Titel für das Produkt ein..." class="tran-all">
                            </div>
                        </div>


                        <div class="fw6 mb12">
                            <p style="color:#5068A1;">Beschreibung</p>
                        </div>

                        <div class="textarea mb42">
                            <div class="textarea-outer">
                                <textarea name="desc" placeholder="Beschreibe das Produkt so ausführlich wie möglich..." class="tran-all"></textarea>
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
                                <input type="text" autocomplete="off" name="price" placeholder="Preis in EURO..." class="tran-all" style="padding-right:62px;width:calc(100% - 32px - 62px);">
                            </div>
                        </div>


                        <!-- CATEGORY -->
                        <div class="boolean-input mt32">

                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Produktkategorie</p>
                            </div>

                            <div class="lt">

                                <div data-element="admin-select" data-input="true" data-list-size="312" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                                    <div class="outline disfl fldirrow">
                                        <p class="text">Auswählen</p>
                                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                                    </div>

                                    <datalist class="tran-all-cubic">
                                        <ul>
                                            <?php

                                            $selCat = $pdo->prepare("SELECT * FROM products_categories ORDER BY id DESC");
                                            $selCat->execute();

                                            foreach ($selCat->fetchAll() as $cat) {

                                            ?>
                                                <li class="trimfull" data-json='[{"id":"<?php echo $cat->id; ?>"}]'>
                                                    <?php echo $cat->name; ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                        <input type="hidden" name="cid">
                                    </datalist>
                                </div>
                            </div>

                            <div class="cl"></div>
                        </div>


                        <!-- MWSTR -->
                        <div class="boolean-input mt32">

                            <div class="fw6 mb8">
                                <p style="color:#5068A1;">Mehrwert-Steuer</p>
                            </div>

                            <div class="desc-bool">

                                <p class="lt text">Sofern das Produkt eine Mehrwert-Steuer enthält, kann diese Option aktiviert werden. Die derzeitige Steuer beträgt <strong><?php echo $main["mwstr"]; ?> %</strong>.</p>

                                <div class="bool rt">
                                    <div class="boolean-great" data-element="boolean-great">
                                        <div class="outer tran-all">
                                            <div class="actual mshd-1 tran-all-cubic">
                                                <div class="booler"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="mwstr" value="0">
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
                                    <div class="boolean-great on" data-element="boolean-great">
                                        <div class="outer tran-all">
                                            <div class="actual mshd-1 tran-all-cubic">
                                                <div class="booler"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="available" value="1">
                                    </div>
                                </div>

                                <div class="cl"></div>
                            </div>

                        </div>


                        <button type="submit" class="btn-outline rt mt32" style="border-color:#AC49BD;color:#AC49BD;">
                            <p>Hinzufügen</p>
                        </button>

                    </form>

                    <div class="cl"></div>
                </div>
            </div>
        </content-card>

    </wide-container>

<?php

} else {
    exit(0);
}

?>