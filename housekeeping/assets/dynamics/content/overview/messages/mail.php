<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['rel']) && $admin->isAdmin()) {

    $rel = $_REQUEST['rel'];

    if (is_numeric($rel)) {

        // check if customer exists
        $sel = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
        $sel->execute([$rel]);


        if ($sel->rowCount() > 0) {

            // fetch stuff
            $s = $sel->fetch();
            $mail = $s->mail;
        } else {
            exit(0);
        }
    } else {

        if (filter_var($rel, FILTER_VALIDATE_EMAIL)) {
            $mail = $rel;
        } else {
            exit(0);
        }
    }

?>

    <wide-container style="padding-top:62px;">

        <form data-form="messages:mail" method="POST" action>

            <!-- INFORMATON BOX -->
            <div class="head-text mb12">
                <p>E-Mail versenden</p>
            </div>

            <content-card class="mb24 posrel">
                <div class="mshd-1 normal-box bgf">
                    <div style="padding:28px 42px;">

                        <div class="textarea">
                            <input type="hidden" name="mail" value="<?php echo $mail; ?>" />

                            <div class="textarea-outer">
                                <textarea name="text" placeholder="Was möchtest du dem Kunden mitteilen?" class="tran-all"></textarea>
                            </div>
                        </div>

                        <div class="info-box hasIcon lila mt24">
                            <p class="icon">
                                <i class="material-icons md-24">help</i>
                            </p>
                            <p class="text">Diese E-mail wird an <strong><?php echo $mail; ?></strong> versendet</p>
                        </div>

                        <button type="submit" class="btn-outline rt mt24 bgf tran-all" style="border-color:#AC49BD;color:#AC49BD;">
                            <p>Mitteilung versenden</p>
                        </button>

                        <div class="cl"></div>

                    </div>
                </div>
            </content-card>

        </form>

    </wide-container>

<?php

} else {
    exit(0);
}

?>