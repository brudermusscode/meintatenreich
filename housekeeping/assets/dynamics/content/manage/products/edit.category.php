<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $id = $_REQUEST['id'];


    // SELECT: PARAMS, FETCH, CHECK NUM ROWS
    $sel = $c->prepare("SELECT * FROM products_categories WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        $sc = $sr->fetch_assoc();

?>


        <wide-container style="padding-top:62px;">


            <!-- EDIT GENERAL -->
            <div class="head-text mb12">
                <p>Kategorie bearbeiten</p>
            </div>


            <content-card class="mb42 posrel">
                <div class="mshd-1 normal-box">
                    <div style="padding:32px 42px;">

                        <form data-form="manage:products,category,edit">

                            <input name="id" type="hidden" value="<?php echo $id; ?>">

                            <!-- TITLE -->
                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Name</p>
                            </div>

                            <div class="input tran-all-cubic mb32">
                                <div class="input-outer">
                                    <input type="text" autocomplete="off" name="name" placeholder="Gib einen Namen für die Kategorie ein..." value="<?php echo $sc['name']; ?>" class="tran-all">
                                </div>
                            </div>

                        </form>

                        <div>
                            <div data-action="manage:products,category,edit,confirm" class="btn-outline rt" style="border-color:#AC49BD;color:#AC49BD;">
                                <p>Speichern</p>
                            </div>

                            <div data-action="manage:products,category,edit,remove" data-color="#C2185B" class="btn-outline rt mr12 tran-all" style="border-color:#C2185B;color:#C2185B;">
                                <p class="tran-all">Löschen</p>
                            </div>

                            <div class="cl"></div>
                        </div>
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
                $sel = $c->prepare("
                    SELECT *, products.id AS pid 
                    FROM products, products_images 
                    WHERE products.id = products_images.pid
                    AND products_images.isgal = '1'
                    AND products.cid = ?
                    ORDER BY products.id DESC
                ");
                $sel->bind_param('s', $id);
                $sel->execute();
                $sel_r = $sel->get_result();
                $sel->close();

                if ($sel_r->rowCount() < 1) {

                ?>
                    <content-card class="mb42 posrel">
                        <div class="mshd-1 normal-box">
                            <div style="padding:32px 42px;">
                                <div class="tac">
                                    <p class="mb12"><i class="material-icons md-42">hourglass_empty</i></p>
                                    <p class="fw4">Keine Produkte in dieser Kategorie</p>
                                </div>
                            </div>
                        </div>
                    </content-card>

                <?php

                } else {

                ?>

                    <content-card class="mb42 posrel">
                        <div class="mshd-1 normal-box">
                            <div style="padding:32px 42px;">
                                <p>Bei Löschung der Kategorie werden alle Produkte in die Kategorie <strong>"Keine Kategorie"</strong> verschoben.</p>
                            </div>
                        </div>
                    </content-card>

                    <?php

                    while ($s = $sel_r->fetch_assoc()) {

                        $pid = $s['pid'];

                        $res = false;
                        $selres = $c->prepare("SELECT * FROM products_reserved WHERE pid = ? AND active = 1");
                        $selres->bind_param('s', $pid);
                        $selres->execute();
                        $selres_r = $selres->get_result();
                        $selres->close();

                        if ($selres_r->rowCount() > 0) {
                            $res = true;
                        }

                    ?>

                        <content-card class="mb24 lt tripple">
                            <div class="products mshd-1 adjust">

                                <div class="image">
                                    <div class="image-outer">
                                        <div class="actual">
                                            <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $s['url']; ?>">
                                        </div>
                                    </div>

                                    <div class="overlay">
                                        <div class="bottom">
                                            <div class="price rt">
                                                <p>EUR <?php echo number_format($s['price'], 2, ',', '.'); ?></p>
                                            </div>

                                            <div class="cl"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="inr-content">
                                    <div class="name">
                                        <p class="trimfull"><?php echo $s['name']; ?></p>
                                    </div>

                                    <div class="artnr">
                                        <p class="ttup tac fw4 lt mr8">
                                            <i class="material-icons md-18 lh32">bookmark</i>
                                        </p>
                                        <p class="ttup tac fw4 lh32 lt"><?php echo $s['artnr']; ?></p>

                                        <div class="cl"></div>
                                    </div>

                                    <div class="av rt">

                                        <?php if ($res === true) { ?>

                                            <div class="av-outer o">
                                                <p class="ttup">Reserviert</p>
                                            </div>

                                        <?php } else { ?>

                                            <?php if ($s['available'] === '1') { ?>
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

                <?php

                    } // END WHILE: ORDERS 
                } // END IF: NUM ROWS
                ?>

                <div class="cl"></div>
            </div>

        </wide-container>


<?php

    } else {
        exit('1'); // Category doesn't exist
    }
} else {
    exit;
}

?>