<?php

if (isset($elementInclude) && $admin->isAdmin()) {

    $id = $elementInclude->pid;

?>

    <content-card class="mb24 lt tripple">
        <div class="products hd-shd adjust">

            <div class="image">
                <div class="image-outer">
                    <div class="actual">
                        <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $elementInclude->url; ?>">
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
                                            <p class="lt ne trimfull">Bearbeiten</p>

                                            <div class="cl"></div>
                                        </li>

                                        <li class="wic" data-action="manage:products,toggle" data-json='[{"id":"<?php echo $id; ?>"}]'>
                                            <?php if ($elementInclude->available == "1") { ?>
                                                <p class="ic lt"><i class="material-icons md-18">visibility_off</i></p>
                                                <p class="lt ne trimfull">Deaktivieren</p>
                                            <?php } else { ?>
                                                <p class="ic lt"><i class="material-icons md-18">visibility_on</i></p>
                                                <p class="lt ne trimfull">Aktivieren</p>
                                            <?php } ?>
                                            <div class="cl"></div>
                                        </li>

                                        <div class="dist" style="width:100%;margin:12px 0;border-bottom:1px solid rgba(0,0,0,.04);"></div>

                                        <li class="wic" data-action="manage:product,delete" data-json='[{"id":"<?php echo $id; ?>"}]'>
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
                            <p>EUR <?php echo number_format($elementInclude->price, 2, ',', '.'); ?></p>
                        </div>

                        <div class="cl"></div>
                    </div>
                </div>
            </div>

            <div class="inr-content">
                <div class="name">
                    <p class="trimfull"><?php echo $elementInclude->name; ?></p>
                </div>

                <div class="artnr">
                    <p class="ttup tac fw4 lt mr8">
                        <i class="material-icons md-18 lh32">bookmark</i>
                    </p>
                    <p class="ttup tac fw4 lh32 lt"><?php echo $elementInclude->artnr; ?></p>

                    <div class="cl"></div>
                </div>

                <div class="av rt">

                    <?php if ($elementInclude->available === '1') { ?>
                        <div class="av-outer g">
                            <p class="ttup">Verfügbar</p>
                        </div>
                    <?php } else { ?>
                        <div class="av-outer r">
                            <p class="ttup">Nicht verfügbar</p>
                        </div>
                    <?php } ?>

                </div>

                <div class="cl"></div>
            </div>

        </div>
    </content-card>

<?php

} else {
    exit;
}

?>