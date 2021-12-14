<div class="hd-shd posfix tran-all-cubic" data-action="open:menu,main">
    <p class="inr" data-tip="Hauptmenü öffnen" class="alt">
        <i class="material-icons md-24">keyboard_arrow_right</i>
    </p>
</div>


<main-navigation data-structure="navigation" data-react="open:menu,main" class="tran-all-cubic">

    <div class="inr">

        <div class="menu mt24">
            <ul>
                <a href="<?php echo $url["main"]; ?>">
                    <li class="point tran-all">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">arrow_back_ios</i>&nbsp;</p>
                            <p class="text">zurück zum Shop</p>
                        </div>
                    </li>
                </a>
            </ul>
        </div>

        <div class="menu-heading">
            <p>Übersicht</p>
        </div>

        <style>
            .changelog {
                background: rgba(228, 79, 213, 1);
                padding: 8px 0;
                border-bottom: 1px solid rgba(255, 255, 255, .12);
                border-top: 1px solid rgba(255, 255, 255, .12);
                margin-bottom: 12px;
                transition: all .1s linear;
            }

            .changelog:hover {
                background: rgba(166, 88, 246, 1);
            }

            .changelog p {
                color: white !important;
            }

            .changelog .inr {
                padding: 0 38px 2px;
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
                align-items: flex-start;
                line-height: 38px !important;
                height: 38px;
                overflow: hidden;
            }

            .changelog .inr .icon {
                margin-right: 24px;
                margin-top: 8px;
            }

            .changelog .inr .text {
                font-size: 0.8em;
                font-weight: 600;
                padding-top: 2px;
            }
        </style>

        <div class="menu">
            <ul>

                <a href="<?php echo $url["dashbrd"]; ?>/changelog">
                    <li class="tran-all <?php if ($pid === 'overview:changelog') {
                                            echo 'active';
                                        } ?> changelog">
                        <div class="disfl fldirrow ph24 inr">
                            <p class="icon"><i class="material-icons md-24">change_circle</i>&nbsp;</p>
                            <p class="text">Was ist neu?</p>
                        </div>
                    </li>
                </a>

                <a href="<?php echo $url["dashbrd"]; ?>/black">
                    <li class="point tran-all <?php if ($pid === 'aindex') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">weekend</i>&nbsp;</p>
                            <p class="text">Startseite</p>
                        </div>
                    </li>
                </a>

                <a data-action="overview:messages,check">
                    <li class="point tran-all <?php if ($pid === 'overview:messages') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">inbox</i>&nbsp;</p>
                            <p class="text">Nachrichten</p>
                        </div>
                    </li>
                </a>

            </ul>
        </div>

        <div class="menu-heading">
            <p>verwaltung</p>
        </div>

        <div class="menu">
            <ul>

                <a href="<?php echo $url["dashbrd"]; ?>/manage/app">
                    <li class="point tran-all <?php if ($pid === 'manage:app') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">settings</i>&nbsp;</p>
                            <p class="text">Shop</p>
                        </div>
                    </li>
                </a>

                <a href="<?php echo $url["dashbrd"]; ?>/manage/orders">
                    <li class="point tran-all <?php if ($pid === 'manage:orders') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">euro_symbol</i>&nbsp;</p>
                            <p class="text">Bestellungen</p>
                        </div>
                    </li>
                </a>

                <a href="<?php echo $url["dashbrd"]; ?>/manage/customers">
                    <li class="point tran-all <?php if ($pid === 'manage:customers') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">supervised_user_circle</i>&nbsp;</p>
                            <p class="text">Kunden</p>
                        </div>
                    </li>
                </a>

                <a href="<?php echo $url["dashbrd"]; ?>/manage/products">
                    <li class="point tran-all <?php if ($pid === 'manage:products') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">casino</i>&nbsp;</p>
                            <p class="text">Produkte</p>
                        </div>
                    </li>
                </a>

                <a href="<?php echo $url["dashbrd"]; ?>/manage/ratings">
                    <li class="point tran-all <?php if ($pid === 'manage:ratings') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">star</i>&nbsp;</p>
                            <p class="text">Bewertungen</p>
                        </div>
                    </li>
                </a>

                <a href="<?php echo $url["dashbrd"]; ?>/manage/courses">
                    <li class="point tran-all <?php if ($pid === 'manage:courses') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">golf_course</i>&nbsp;</p>
                            <p class="text">Kurse</p>
                        </div>
                    </li>
                </a>

                <a target="_blank" href="<?php echo $url["mysql"]; ?>">
                    <li class="point tran-all">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">data_usage</i>&nbsp;</p>
                            <p class="text">phpMyAdmin</p>
                        </div>
                    </li>
                </a>

            </ul>
        </div>

        <div class="menu-heading">
            <p>funktionen</p>
        </div>

        <div class="menu">
            <ul>

                <a href="<?php echo $url["dashbrd"]; ?>/functions/mailer">
                    <li class="point tran-all <?php if ($pid === 'func:mailer') {
                                                    echo 'active';
                                                } ?>">
                        <div class="point-inr">
                            <p class="icon"><i class="material-icons md-24">mark_as_unread</i>&nbsp;</p>
                            <p class="text">Mailer</p>
                        </div>
                    </li>
                </a>

            </ul>
        </div>

        <div style="height:32px;width:1px;"></div>

    </div>

    <div class="menu-footing">
        <div class="ftr-inr">
            <p>2018 &copy; Justin Seidel</p>
        </div>
    </div>

</main-navigation>