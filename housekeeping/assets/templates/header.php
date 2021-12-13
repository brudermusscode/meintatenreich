<?php

$getAdminMailSettings = $pdo->prepare("SELECT * FROM admin_mails_settings WHERE id = '1'");
$getAdminMailSettings->execute();

if ($getAdminMailSettings->rowCount() > 0) {

    $mailsCh = true;
    $mailsChecked = $getAdminMailSettings->fetch();

    if ($mailsChecked->mails_checked == '0') {
        $mailsCh = false;
    }
}

?>

<style>
    .mc-heading .menu-outer ul.menu {
        list-style: none;
    }

    .mc-heading .menu-outer ul.menu li {
        line-height: 42px;
        height: 42px;
        width: 42px;
        background: rgba(255, 255, 255, .8);
        text-align: center;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
    }

    .mc-heading .menu-outer ul.menu li:hover {
        opacity: .8;
    }

    .mc-heading .menu-outer ul.menu li:active {
        opacity: .6;
    }

    .mc-heading .menu-outer ul.menu li p {
        color: #A247C0;
    }
</style>


<div class="mc-heading">
    <div class="lt left-content">

        <div class="lt">
            <div class="menu-outer">
                <ul class="menu">

                </ul>
            </div>
        </div>

        <div class="cl"></div>
    </div>

    <div class="rt right-content">
        <div class="rt">

            <div class="user-image lin-bg-purple hd-shd tran-all posrel linear-background purple" data-action="overview:messages,check">
                <div style="height:calc(100% - 4px);width:calc(100% - 4px);position:absolute;top:0;left:0;border-radius:20px;border:2px solid rgba(255,255,255,.32);"></div>
                <div class="pulse <?php if ($mailsCh === false) echo 'active'; ?>"></div>

                <div class="actual">
                    <p><i class="material-icons md-24 lh42">chat_bubble_outline</i></p>
                </div>
            </div>
        </div>

        <div class="cl"></div>
    </div>

    <div class="cl"></div>


    <!--- heading in main content --->
    <?php if ($pid == "aindex") { ?>

        <div class="mm-heading">
            <p class="title lt lh42">Übersicht</p>
            <div class="tools lt ml32">
                <div data-element="admin-select" data-action="manage:filter" data-page="overview" data-list-size="212" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                    <div class="outline disfl fldirrow">
                        <p class="text">Filtern</p>
                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                    </div>

                    <datalist class="tran-all-cubic left">
                        <ul>
                            <li class="trimfull" data-json='[{"order":"#nofilter"}]'>Alle anzeigen</li>
                            <li class="trimfull" data-json='[{"order":"orders"}]'>Bestellungen</li>
                            <li class="trimfull" data-json='[{"order":"customers"}]'>Kunden</li>
                            <li class="trimfull" data-json='[{"order":"ratings"}]'>Bewertungen</li>
                        </ul>
                    </datalist>
                </div>
            </div>

            <style>
                .mm-heading .tools .change-view {
                    line-height: 42px;
                    padding-top: 6px;
                }

                .mm-heading .tools .change-view ul {
                    list-style: none;
                    display: flex;
                    flex-direction: row;
                }

                .mm-heading .tools .change-view ul li i {
                    list-style: none;
                    display: flex;
                    flex-direction: row;
                }

                .mm-heading .tools .change-view ul li {
                    opacity: .6;
                    cursor: pointer;
                }

                .mm-heading .tools .change-view ul li:hover {
                    opacity: 1;
                }

                .mm-heading .tools .change-view ul li.active {
                    opacity: 1;
                }
            </style>

            <div class="tools rt disn">
                <div class="change-view">
                    <ul>
                        <li class="tran-all"><i class="material-icons md-32">view_module</i></li>
                        <li class="tran-all active"><i class="material-icons md-32">view_stream</i></li>
                    </ul>
                </div>
            </div>

            <div class="cl"></div>
        </div>

    <?php } else if ($pid == "manage:orders") { ?>

        <div class="mm-heading" data-closeout="manage:filter">
            <p class="title lt lh42">Bestellungen</p>
            <div class="tools lt ml32">
                <div data-element="admin-select" data-action="manage:filter" data-page="orders" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                    <div class="outline disfl fldirrow">
                        <p class="text">Filtern</p>
                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                    </div>

                    <datalist class="tran-all-cubic left">
                        <ul>
                            <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                            <li class="trimfull" data-json='[{"order":"got"}]'>Neue Bestellungen</li>
                            <li class="trimfull" data-json='[{"order":"sent"}]'>Versandte</li>
                            <li class="trimfull" data-json='[{"order":"done"}]'>Abgeschlossene</li>
                            <li class="trimfull" data-json='[{"order":"canceled"}]'>Stornierte</li>
                            <li class="trimfull" data-json='[{"order":"unpaid"}]'>Nicht bezahlte</li>
                            <li class="trimfull" data-json='[{"order":"paidmarked"}]'>Als bezahlt markierte</li>
                            <li class="trimfull" data-json='[{"order":"paid"}]'>Bezahlte</li>
                        </ul>
                    </datalist>
                </div>
            </div>

            <div class="cl"></div>
        </div>

    <?php } else if ($pid == "manage:customers") { ?>

        <div class="mm-heading">
            <p class="title lt lh42">Alle Kunden</p>
            <div class="tools lt ml32">
                <div data-element="admin-select" data-action="manage:filter" data-page="customers" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                    <div class="outline disfl fldirrow">
                        <p class="text">Filtern</p>
                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                    </div>

                    <datalist class="tran-all-cubic left">
                        <ul>
                            <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                            <li class="trimfull" data-json='[{"order":"verified"}]'>Verifiziert</li>
                            <li class="trimfull" data-json='[{"order":"unverified"}]'>Nicht verifiziert</li>
                        </ul>
                    </datalist>
                </div>
            </div>

            <div class="cl"></div>
        </div>

    <?php } else if ($pid == "manage:products") { ?>

        <div class="mm-heading" style="padding-bottom:0;">
            <p class="title lt">Produktkategorien</p>
            <div class="cl"></div>

            <div class="mb24 mt24" data-react="manage:products,category,add">

                <?php

                $getProductsCategories = $pdo->prepare("SELECT * FROM products_categories ORDER BY id DESC");
                $getProductsCategories->execute();

                foreach ($getProductsCategories->fetchAll() as $s) {

                ?>

                    <content-card class="lt mr8 mb8" data-id="<?php echo $s->id; ?>" data-element="products:category">
                        <div class="normal-box adjust curpo lh36 ph12" data-action="manage:products,category,edit" data-json='[{"id":"<?php echo $s->id; ?>"}]'>
                            <div>
                                <p class="fw4" style="white-space:nowrap;"><?php echo $s->category_name; ?></p>
                            </div>
                        </div>
                    </content-card>

                <?php } ?>



                <content-card class="lt mr8 posrel" data-action="manage:products,category,add">
                    <div class="normal-box adjust">
                        <div class="lh36 ph12" style="height:36px;overflow:hidden;color:#A247C0;cursor:pointer;white-space:nowrap">
                            <p class="lt mr8"><i class="material-icons md-18 lh36">add</i></p>
                            <p class="fw5 lt">Hinzufügen</p>

                            <div class="cl"></div>
                        </div>
                    </div>
                </content-card>

                <div class="cl"></div>
            </div>
        </div>

        <!-- ALL PRODUCTS-->
        <div class="mm-heading" style="padding-top:0px;">
            <p class="title lt lh42">Alle Produkte</p>
            <div class="tools lt ml32">
                <div data-element="admin-select" data-action="manage:filter" data-page="products" data-list-size="212" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                    <div class="outline disfl fldirrow">
                        <p class="text">Filtern</p>
                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                    </div>

                    <datalist class="tran-all-cubic left">
                        <ul>
                            <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                            <li class="trimfull" data-json='[{"order":"available"}]'>Verfügbare</li>
                            <li class="trimfull" data-json='[{"order":"unavailable"}]'>Nicht verfügbare</li>
                            <li class="trimfull" data-json='[{"order":"priceup"}]'>Preis aufwärts</li>
                            <li class="trimfull" data-json='[{"order":"pricedown"}]'>Preis abwärts</li>
                            <li class="trimfull" data-json='[{"order":"archived"}]'>Archivierte</li>
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

    <?php } else if ($pid == "manage:ratings") { ?>

        <div class="mm-heading" data-closeout="manage:filter">
            <p class="title lt lh42">Bewertungen</p>
            <div class="tools lt ml32">
                <div data-element="admin-select" data-action="manage:filter" data-page="ratings" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                    <div class="outline disfl fldirrow">
                        <p class="text">Filtern</p>
                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                    </div>

                    <datalist class="tran-all-cubic left">
                        <ul>
                            <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                        </ul>
                    </datalist>
                </div>
            </div>

            <div class="cl"></div>
        </div>

    <?php } else if ($pid == "manage:app") { ?>

        <div class="mm-heading" data-closeout="manage:filter">
            <p class="title lt lh42" style="font-weight:700;">Applikations-Einstellungen</p>
            <div class="cl"></div>
        </div>

    <?php } else if ($pid == "manage:courses") { ?>

        <div class="mm-heading">
            <p class="title lt lh42">Kurse</p>
            <div class="tools lt ml32">
                <div data-element="admin-select" data-action="manage:filter" data-page="courses" data-list-size="212" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                    <div class="outline disfl fldirrow">
                        <p class="text">Filtern</p>
                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                    </div>

                    <datalist class="tran-all-cubic left">
                        <ul>
                            <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                            <li class="trimfull" data-json='[{"order":"archived"}]'>Archivierte</li>
                        </ul>
                    </datalist>
                </div>
            </div>

            <div class="rt">
                <div class="mshd-1" style="color:#A247C0;border-radius:50px;background:white;cursor:pointer;padding:0 18px;" data-action="manage:courses,add">
                    <p class="lt mr12"><i class="material-icons md-24 lh42">add</i></p>
                    <p class="lt lh42">Kurs hinzufügen</p>

                    <div class="cl"></div>
                </div>
            </div>

            <div class="cl"></div>
        </div>

    <?php } else if ($pid == "overview:messages") { ?>

        <div class="mm-heading">
            <p class="title lt lh42">Nachrichten</p>
            <div class="tools lt ml32">

                <div class="chooser" data-element="chooser" data-action="overview:messages,panel">
                    <ul class="outer">
                        <li class="point tran-all active" data-order="got">
                            <p class="icon lt mr12">
                                <i class="material-icons md-24 lh42">call_received</i>
                            </p>
                            <p class="text lt">Erhalten</p>

                            <div class="cl"></div>
                        </li>
                        <li class="point tran-all" data-order="sent">
                            <p class="icon lt mr12">
                                <i class="material-icons md-24 lh42">call_made</i>
                            </p>
                            <p class="text lt">Gesendet</p>

                            <div class="cl"></div>
                        </li>
                        <li class="point tran-all green" data-order="fav">
                            <p class="icon lt mr12">
                                <i class="material-icons md-24 lh42">star</i>
                            </p>
                            <p class="text lt">Gemerkt</p>

                            <div class="cl"></div>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="cl"></div>
        </div>

    <?php } else if ($pid == "functions:mailer") { ?>

        <div class="mm-heading" data-closeout="manage:filter">
            <p class="title lt lh42">Mailer</p>
            <div class="tools lt ml32">
                <div data-element="admin-select" data-action="func:mailer,choose" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                    <div class="outline disfl fldirrow">
                        <p class="text">Auswählen</p>
                        <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                    </div>

                    <datalist class="tran-all-cubic">
                        <ul>
                            <li class="trimfull" data-json='[{"mail":"all"}]'>Rundmail</li>
                            <li class="trimfull" data-json='[{"mail":"single"}]'>Einzelmail</li>
                        </ul>
                    </datalist>
                </div>
            </div>

            <div class="cl"></div>
        </div>

    <?php } ?>



</div>