<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$validId = ['all', 'single'];

if (isset($_REQUEST['id']) && $admin->isAdmin()) {

    $id = htmlspecialchars($_REQUEST['id']);

    if (in_array($id, $validId)) {

        if ($id === 'all') {

?>

            <content-card class="mb24">
                <div class="hd-shd adjust bgf" data-react="show:loader">
                    <div style="padding:32px 42px">

                        <div class="fw6 mb24">
                            <p style="color:#5068A1;">Rundmail versenden</p>
                        </div>

                        <form data-form="functions:mailer,roundmail" method="POST" action>

                            <div class="textarea">

                                <div class="textarea-outer">
                                    <textarea class="tran-all" name="mail" placeholder="Was möchtest du den Kunden mitteilen?"></textarea>
                                </div>

                            </div>

                            <div class="info-box lila fw4 mt12 hasIcon">
                                <p class="icon"><i class="material-icons md-24">help</i></p>
                                <p class="text">Bitte beachte, dass diese Mail an alle Kunden, welche sich für diesen Shop registriert haben, verschickt wird.</p>
                            </div>

                            <button type="submit" class="btn-outline rt mt24 bgf tran-all" style="border-color:#AC49BD;color:#AC49BD;">
                                <p>Rundmail versenden</p>
                            </button>

                        </form>

                        <div class="cl"></div>

                    </div>
                </div>
            </content-card>

        <?php

        } else if ($id === 'single') {

        ?>

            <content-card class="mb24">
                <div class="hd-shd adjust bgf" data-react="show:loader">
                    <div style="padding:32px 42px">

                        <div class="fw6 mb24">
                            <p style="color:#5068A1;">Einzelmail versenden</p>
                        </div>

                        <form data-form="func:mailer">

                            <div class="textarea">

                                <div class="textarea-outer">
                                    <textarea disabled="disabled" class="tran-all" name="mail" placeholder="Was möchtest du dem Kunden mitteilen?"></textarea>
                                </div>

                            </div>

                            <div class="info-box lila fw4 mt12 hasIcon">
                                <p class="icon"><i class="material-icons md-24">help</i></p>
                                <p class="text">Nachrichten, die hier versendet werden, können später im Nachrichten-Center unter "Gesendet" eingesehen werden.</p>
                            </div>

                            <div disabled="disabled" data-action="func:mailer,send" data-wh="single" class="btn-outline rt mt24" style="border-color:#AC49BD;color:#AC49BD;">
                                <p>Einzelmail versenden</p>
                            </div>

                        </form>

                        <div class="cl"></div>

                    </div>
                </div>
            </content-card>

<?php

        }
    } else {
        exit;
    }
} else {
    exit;
}
