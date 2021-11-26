<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'open-settings' && $loggedIn) {

    if ($_REQUEST['which'] !== false) {

        $which = htmlspecialchars($_REQUEST['which']);
    }


?>

    <?php if ($which === 'set-name') { ?>
        <script>
            $('wide-container').find('input[name="firstname"]').focus();
        </script>
    <?php } ?>


    <wide-container class="almid posabs">
        <div class="mshd-2 rd5 zoom-in bgf">
            <div class="hd mshd-1">
                <p>Übersichts-Einstellungen</p>
            </div>

            <div class="close tran-all" data-action="close-overlay">
                <p><i class="icon-cancel-5"></i></p>
            </div>

            <div class="body">
                <div class="desc">
                    <p>Für Sicherheitseinstellungen gehe bitte zur <a href="/a/security">Sicherheits-Abteilung</a></p>
                </div>

                <form data-form="settings">

                    <div class="option w100">
                        <div class="input w100">
                            <p>Anzeigename</p>
                            <div class="actual w100">
                                <input type="text" name="displayname" placeholder="<?php echo $my->displayname; ?>" class="tran-all">
                            </div>
                        </div>
                    </div>

                    <div class="disfl fldirrow">
                        <div class="option w50 mr12">
                            <div class="input w100">
                                <p>Vorname</p>
                                <div class="actual w100">
                                    <input type="text" name="firstname" placeholder="<?php echo $my->firstname; ?>" class="tran-all">
                                </div>
                            </div>
                        </div>

                        <div class="option w50">
                            <div class="input w100">
                                <p>Nachname</p>
                                <div class="actual w100">
                                    <input type="text" name="secondname" placeholder="<?php echo $my->secondname; ?>" class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="option w100">
                        <div class="input w100">
                            <p>E-Mail Adresse</p>
                            <div class="actual w100">
                                <input disabled type="text" name="mail" placeholder="<?php echo $my->mail; ?>" class="tran-all">
                            </div>
                        </div>
                    </div>

                </form>

            </div>
            <div data-react="save-settings" class="responsive-line tran-all"></div>
        </div>
    </wide-container>


<?php

} else {

    exit("0");
}

?>