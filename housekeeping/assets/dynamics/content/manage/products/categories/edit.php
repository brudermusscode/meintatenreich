<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && $admin->isAdmin()) {

    $id = $_REQUEST['id'];

    $sel = $pdo->prepare("SELECT * FROM products_categories WHERE id = ?");
    $sel->execute([$id]);

    if ($sel->rowCount() > 0) {

        $sc = $sel->fetch();

?>


        <wide-container style="padding-top:62px;">


            <!-- EDIT GENERAL -->
            <div class="head-text mb12">
                <p>Kategorie bearbeiten</p>
            </div>


            <content-card class="mb42 posrel">
                <div class="mshd-1 normal-box">
                    <div style="padding:32px 42px;">

                        <?php if ($sc->id == 0) { ?>

                            <p>Produkte, die keiner Kategorie untergeordnet sind.</p>

                        <?php } else { ?>

                            <form data-form="manage:products,category,edit">

                                <input name="id" type="hidden" value="<?php echo $id; ?>">

                                <!-- TITLE -->
                                <div class="fw6 mb12">
                                    <p style="color:#5068A1;">Name</p>
                                </div>

                                <div class="input tran-all-cubic mb32">
                                    <div class="input-outer">
                                        <input type="text" autocomplete="off" name="name" placeholder="Gib einen Namen für die Kategorie ein..." value="<?php echo $sc->category_name; ?>" class="tran-all">
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="btn-outline rt" style="border-color:#AC49BD;color:#AC49BD;">
                                        <p>Speichern</p>
                                    </button>

                                    <div data-action="manage:products,category,edit,remove" data-id="<?php echo $id; ?>" data-color="#C2185B" class="btn-outline rt mr12 tran-all" style="border-color:#C2185B;color:#C2185B;">
                                        <p class="tran-all">Löschen</p>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                            </form>

                        <?php } ?>

                    </div>
                </div>
            </content-card>


            <!-- PRODUCTS -->
            <div class="head-text mb12">
                <p>Produkte dieser Kategorie</p>
            </div>

            <div class="mb42">
                <?php

                // GET ALL ORDERS & USER INFORMATION
                $getProducts = $pdo->prepare("
                    SELECT *, products.id AS pid 
                    FROM products, products_images, products_categories  
                    WHERE products.id = products_images.pid
                    AND products.cid = products_categories.id
                    AND products_images.isgal = '1'
                    AND products.cid = ?
                    AND products.deleted = '0' 
                    ORDER BY products.id DESC
                ");
                $getProducts->execute([$id]);

                if ($getProducts->rowCount() < 1) {

                ?>
                    <content-card class="mb42 posrel">
                        <div class="mshd-1 normal-box">
                            <div style="padding:32px 42px;">
                                <div class="tac">
                                    <p class="mb12"><i class="material-icons md-42">hourglass_empty</i></p>
                                    <p class="fw4">Keine Produkte</p>
                                </div>
                            </div>
                        </div>
                    </content-card>

                    <?php

                } else {

                    if (!$sc->id == 0) { ?>

                        <content-card class="mb42 posrel">
                            <div class="mshd-1 normal-box">
                                <div style="padding:32px 42px;">
                                    <p>Bei Löschung der Kategorie werden alle Produkte in die Kategorie <strong>"Keine Kategorie"</strong> verschoben.</p>
                                </div>
                            </div>
                        </content-card>

                <?php

                    }

                    foreach ($getProducts->fetchAll() as $elementInclude) {

                        $tripple = true;
                        include $sroot . "/housekeeping/assets/dynamics/elements/products.php";
                    }
                }

                ?>

                <div class="cl"></div>
            </div>

        </wide-container>


<?php

    } else {
        exit(0);
    }
} else {
    exit(0);
}

?>