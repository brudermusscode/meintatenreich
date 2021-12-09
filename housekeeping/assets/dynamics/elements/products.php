<?php

if (isset($elementInclude) && $admin->isAdmin()) {

    $id = $elementInclude->pid;

?>

    <content-card class="mb24 lt <?php if (isset($tripple)) echo "tripple";
                                    else echo "quad"; ?> tran-all" data-json='[{"id":"<?php echo $id; ?>"}]'>
        <div class="products hd-shd adjust">

            <div data-element="overlay:content-card" data-react="manage:products,delete" class="cc-overlay red rd18">
                <div class="cc-overlay--inr">

                    <div class="cc-overlay--delete">
                        <p class="dialogue fw7 mb38 tac">Bist du sicher?</p>

                        <div class="actions disfl flcenter fldircol posrel">
                            <div data-action="manage:products,delete,confirm" class="rd24 lh38 ph32 mr6 ml6 curpo fw6 mb24">
                                <p>Ja!</p>
                            </div>

                            <div data-action="overlay:content-card,close" class="rd24 lh38 ph24 mr6 ml6 curpo fw5">
                                <p>Abbrechen</p>
                            </div>

                            <div class="cl"></div>
                        </div>
                    </div>

                </div>
            </div>

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

                                        <?php if (!$elementInclude->deleted) { ?>

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

                                        <?php } ?>

                                        <li class="wic" data-action="manage:products,delete">
                                            <?php if ($elementInclude->deleted == "1") { ?>
                                                <p class="ic lt"><i class="material-icons md-18">refresh</i></p>
                                                <p class="lt ne trimfull">Wiederherstellen</p>
                                            <?php } else { ?>
                                                <p class="ic lt"><i class="material-icons md-18">archive</i></p>
                                                <p class="lt ne trimfull">Archivieren</p>
                                            <?php } ?>

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
                    <div data-react="manage:products,toggle" class="av-outer <?php if ($elementInclude->available === '1') echo "enabled";
                                                                                else echo "disabled"; ?>">
                        <p class="ttup"></p>
                    </div>
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