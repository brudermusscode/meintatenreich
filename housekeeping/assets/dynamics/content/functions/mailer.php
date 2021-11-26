<?php

require_once "../../../../../mysql/_.session.php";

if (isset($_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $id = htmlspecialchars($_REQUEST['id']);
    $validId = ['all', 'single'];

    if (in_array($id, $validId)) {

        if ($id === 'all') {

?>

            <content-card class="mb24">
                <div class="order hd-shd adjust posrel ovhid" data-react="show:loader">
                    <div style="padding:32px 42px">

                        <div class="fw6 mb24">
                            <p style="color:#5068A1;">Rundmail versenden</p>
                        </div>

                        <form data-form="func:mailer">

                            <div class="textarea">

                                <div class="textarea-outer">
                                    <textarea class="tran-all" name="mail" placeholder="Was möchtest du den Kunden mitteilen?"></textarea>
                                </div>

                            </div>

                            <div class="mt24 disfl fldirrow">
                                <p class="mr12"><i class="material-icons md-24">new_releases</i></p>
                                <p class="">Bitte beachte, dass diese Mail an alle Kunden, welche sich für diesen Shop registriert haben, verschickt wird.</p>

                                <div class="cl"></div>
                            </div>

                            <div data-action="func:mailer,send" data-wh="all" class="btn-outline rt mt24" style="border-color:#AC49BD;color:#AC49BD;">
                                <p>Rundmail versenden</p>
                            </div>

                        </form>

                        <div class="cl"></div>

                    </div>
                </div>
            </content-card>

        <?php

        } else if ($id === 'single') {

        ?>

            <content-card class="mb24">
                <div class="order hd-shd adjust posrel ovhid" data-react="show:loader">
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

                            <div class="mt24 disfl fldirrow">
                                <p class="mr12"><i class="material-icons md-24">new_releases</i></p>
                                <p class="">Nachrichten, die hier versendet werden, können später im Nachrichten-Center unter "Gesendet" eingesehen werden.</p>

                                <div class="cl"></div>
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
