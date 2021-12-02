<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['rel']) && $admin->isAdmin()) {

    $rel = $_REQUEST['rel'];

    if (is_numeric($rel)) {

        // CHECK IF CUSTOMER EXISTS
        $sel = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
        $sel->bind_param('s', $rel);
        $sel->execute();


        if ($sel->rowCount() > 0) {

            // FETCH ORDER
            $s = $sel->fetch();
            $sel->close();

            $mail = $s['mail'];
        } else {
            exit('1');
        }
    } else {

        if (filter_var($rel, FILTER_VALIDATE_EMAIL)) {
            $mail = $rel;
        } else {
            exit('1');
        }
    }

?>

    <wide-container style="padding-top:62px;" data-json='[{"to":"<?php echo $mail; ?>"}]'>


        <!-- INFORMATON BOX -->
        <div class="head-text mb12">
            <p>E-Mail versenden</p>
        </div>
        <content-card class="mb24 posrel">
            <div class="mshd-1 normal-box">
                <div style="padding:28px 42px;">

                    <p class="mb32">
                        Diese E-mail wird an <strong><?php echo $mail; ?></strong> versendet
                    </p>

                    <div class="textarea">

                        <div class="textarea-outer">
                            <textarea name="custommail" placeholder="Was möchtest du dem Kunden mitteilen?"></textarea>
                        </div>

                    </div>

                    <div data-action="mail:custom,send" class="btn-outline rt mt24" style="border-color:#AC49BD;color:#AC49BD;" data-json='[{"id":"<?php echo $rel; ?>","to":"<?php echo $mail; ?>"}]'>
                        <p>Mitteilung versenden</p>
                    </div>

                    <div class="cl"></div>

                </div>
            </div>
        </content-card>

    </wide-container>

<?php

} else {
    exit;
}

?>