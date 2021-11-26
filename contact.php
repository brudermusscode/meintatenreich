<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Kontak";
$pid = "contact";
$rgname = 'Kontakt';

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">
            <div class="main-overflow-scroll w100">

                <div class="w68 mt24 posrel lt" style="padding-left:6px;">

                    <form data-form="contact">

                        <div class="w50 lt">
                            <div class>
                                <p>Vorname</p>
                            </div>
                            <div class="input">
                                <div class="actual">
                                    <input class="tran-all" name="firstname" type="text" placeholder="" autocomplete="off" value="<?php if ($loggedIn && strlen($my->firstname) > 0) echo $my->firstname; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="w48 rt">
                            <div class>
                                <p>Nachname</p>
                            </div>
                            <div class="input">
                                <div class="actual">
                                    <input class="tran-all" name="secondname" type="text" placeholder="" autocomplete="off" value="<?php if ($loggedIn && strlen($my->secondname) > 0) echo $my->secondname; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="cl"></div>

                        <div class="mt32">
                            <div class>
                                <p>E-Mail Adresse</p>
                            </div>
                            <div class="input">
                                <div class="actual">
                                    <input class="tran-all" name="ref" type="text" placeholder="" autocomplete="off" value="<?php if ($loggedIn) echo $my->mail; ?>">
                                </div>
                            </div>
                        </div>


                        <div class="mt32">
                            <div class="mb12">
                                <p>Kategorie</p>
                            </div>

                            <div data-element="select" class="">
                                <div class="select rd3 mshd-1">
                                    <p>Auswählen</p>
                                    <p class="ml8"><i class="icon-down-open-1"></i></p>
                                </div>


                                <div class="list multi mshd-2 rd3 tran-all-cubic">
                                    <ul>
                                        <?php

                                        // SELECT: CATEGORIES
                                        $getMailCategories = $pdo->prepare("SELECT * FROM admin_mails_categories ORDER BY id");
                                        $getMailCategories->execute();

                                        foreach ($getMailCategories->fetchAll() as $m) {

                                        ?>

                                            <li style="line-height:1.4;" data-json='[{"id":"<?php echo $m->id; ?>"}]'><?php echo $m->name; ?></li>

                                        <?php } // END WHILE: CAT 
                                        ?>
                                    </ul>
                                </div>

                                <input type="hidden" name="cid" value>
                            </div>

                            <div class="cl"></div>
                        </div>

                        <!-- TEXTAREA -->
                        <div class="mt32">
                            <div class="mb12">
                                <p>Mitteilung</p>
                            </div>

                            <div class="textarea">
                                <div class="actual">
                                    <textarea name="msg" class="w100 tran-all" placeholder="Beschreibe Dein Anliegen so genau wie möglich..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- BUTTON -->
                        <div class="mt32 mb32">
                            <button type="button" data-action="contact:send" class="hellofresh hlf-brown w100 disfl fldirrow jstfycc mt12 tran-all rd3 curpo">
                                <p class="trimfull">Kontaktanfrage senden</p>
                            </button>
                        </div>

                    </form>

                </div>

                <!-- CONTENT: RIGHT -->
                <div class="w28 mt24 posrel rt" style="padding-right:6px;">

                    <div class>
                        <div class="mb12 c6">
                            <p class="mr8 lt"><i class="icon-info-circled"></i></p>
                            <p class="lt fw7">Kontaktaufnahme</p>

                            <div class="cl"></div>
                        </div>
                        <p>Nutze dieses Kontaktformular um uns über Fehler der Webseite (Bugs) aufzuklären, oder aufkommende Fragen zu Produkten, den Versand und Weiteres zu klären.</p>
                    </div>


                    <div class="mt32">
                        <div class="mb12 c6">
                            <p class="mr8 lt"><i class="icon-question"></i></p>
                            <p class="lt fw7">Fragen zu Produkten</p>

                            <div class="cl"></div>
                        </div>
                        <p>Bei Fragen zu Produkten, sollte die Artikelnummer im Feld für die Mitteilung mit angegeben werden.</p>
                    </div>

                </div>

                <div class="cl"></div>
            </div>
        </div>
    </div>
</div>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>