<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $id = $_REQUEST['id'];

    // CHECK IF ORDER EXISTS
    $sel = $c->prepare("
            SELECT *
            FROM customer
            WHERE id = ?
        ");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sel_r = $sel->get_result();
    $sel->close();

    if ($sel_r->rowCount() > 0) {

        // GET INFORMATION
        $s = $sel_r->fetch_assoc();

        $pn = mb_substr($s['firstname'], 0, 1) . mb_substr($s['secondname'], 0, 1);

?>

        <wide-container style="padding-top:122px;" data-json='[{"to":"<?php echo $s['mail']; ?>"}]'>

            <!-- GENERAL -->
            <content-card class="mb42 posrel">
                <div class="mshd-1 normal-box posrel">
                    <div style="padding:28px 42px;">

                        <div class="posabs" style="top:12px;right:24px;">

                            <div class="rt" style="border-radius:50%;height:48px;width:48px;background:rgba(0,0,0,.04);cursor:pointer;">
                                <?php if ($s['verified'] === '1') { ?>
                                    <p style="color:#3EAF5C;" class="alt tac" data-tip="Verifizierter Kunde">
                                        <i class="material-icons md32 lh48">done</i>
                                    </p>
                                <?php } else { ?>
                                    <p style="color:#EA363A;" class="alt tac" data-tip="Nicht verifiziert">
                                        <i class="material-icons md32 lh48">clear</i>
                                    </p>
                                <?php } ?>
                            </div>

                            <?php if ($s['admin'] === '1') { ?>
                                <div class="rt mr12" style="border-radius:50%;height:48px;width:48px;background:rgba(0,0,0,.04);cursor:pointer;">
                                    <p style="color:#3EAF5C;" class="alt tac" data-tip="Administrator">
                                        <i class="material-icons md32 lh48">security</i>
                                    </p>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="posabs almid-h" style="height:84px;width:84px;top:-42px;">
                            <div class="mshd-2" style="height:100%;width:100%;border-radius:50%;background:white;">
                                <?php if (strlen($s['firstname']) > 0 && strlen($s['secondname']) > 0) { ?>
                                    <div style="border-radius:50%;background: rgb(66,83,127);background: linear-gradient(20deg, rgba(66,83,127,1) 0%, rgba(93,31,66,1) 100%);line-height:84px;">
                                        <p class="cf fw4 tac ttup" style="font-size:1.4em;"><?php echo $pn; ?></p>
                                    </div>
                                <?php } else { ?>
                                    <img style="height:100%;" class="ovhid opa0" onload="fadeIn(this)" src="<?php echo $url["img"]; ?>/elem/user.png">
                                <?php } ?>
                            </div>
                        </div>

                        <div style="margin-top:42px;">
                            <p class="fw6 tac trimfull w100" style="color:#383838;font-size:1.6em;line-height:1.2;">
                                <?php

                                if (strlen($s['firstname']) > 0 && strlen($s['secondname']) > 0) {
                                    echo $s['firstname'] . ' ' . $s['secondname'];
                                } else {
                                    echo 'Kein Name';
                                }

                                ?>
                            </p>
                        </div>

                        <div class="mb24" style="margin-top:4px;">
                            <p class="fw6 tac trimfull w100" style="color:#999;font-size:1em;">
                                <?php

                                echo '@' . $s['displayname'];

                                ?>
                            </p>
                        </div>

                        <div class="posrel lt" style="margin-top:4px;background:#AB49BD;padding:0 12px;border-radius:6px;height:36px;overflow:hidden;">
                            <p class="lt cf" style="width:32px;">
                                <i class="material-icons md-18 lh36">mail</i>
                            </p>
                            <p class="lt cf fw4 trimfull lh36" style="width:calc(100% - 32px);font-size:1em;">
                                <?php echo $s['mail']; ?>
                            </p>

                            <div class="cl"></div>
                        </div>

                        <div class="cl"></div>

                    </div>
                </div>
            </content-card>



            <!-- ADDRESSES -->
            <div class="head-text mb12">
                <p>Adressen</p>
            </div>

            <div class="user-field mb42">

                <div class="disfl fldirrow">

                    <?php

                    $selAdr = $c->prepare("SELECT * FROM customer_addresses WHERE uid = ?");
                    $selAdr->bind_param('s', $id);
                    $selAdr->execute();
                    $sAr = $selAdr->get_result();
                    $selAdr->close();

                    if ($sAr->rowCount() < 1) {

                    ?>

                        <content-card class="posrel">
                            <div class="mshd-1 normal-box posrel">
                                <div style="padding:28px 42px;">

                                    <p class="trimfull">Keine Adressen hinzugefügt</p>

                                </div>
                            </div>
                        </content-card>

                    <?php

                    }

                    while ($adr = $sAr->fetch_assoc()) {

                    ?>

                        <content-card class="half">
                            <div class="adjust mshd-1 bg-blue-green">
                                <div class="icon">
                                    <p>
                                        <i class="material-icons em-10">account_circle</i>
                                    </p>
                                </div>
                                <div>

                                    <div class="colorfields-inr">
                                        <div class="type">
                                            <p>Kunde</p>
                                        </div>

                                        <div class="normal-field">
                                            <p><?php echo $adr['fullname']; ?></p>
                                        </div>


                                        <div class="type mt12">
                                            <p>Adresse</p>
                                        </div>

                                        <div class="normal-field">
                                            <p>
                                                <?php
                                                if ($adr['additional'] !== 'none') {
                                                    echo $adr['address'] . ', ' . $adr['additional'];
                                                } else {
                                                    echo $adr['address'];
                                                }
                                                ?>
                                            </p>
                                        </div>

                                        <div class="normal-field">
                                            <p><?php echo $adr['city'] . ', ' . $adr['postcode']; ?></p>
                                        </div>

                                        <?php if (strlen($adr['tel']) > 0) { ?>
                                            <div class="type mt12">
                                                <p>Kontakt</p>
                                            </div>

                                            <div class="normal-field">
                                                <p><?php echo $adr['tel']; ?></p>
                                            </div>
                                        <?php } ?>

                                    </div>


                                </div>
                            </div>
                        </content-card>

                    <?php } // END WHILE: ADDRESSES 
                    ?>

                </div>
            </div>



            <!-- PAYMENT METHODS -->
            <div class="head-text mb12">
                <p>Zahlungsmethoden</p>
            </div>

            <div class="user-field mb42">

                <div class="disfl fldirrow">

                    <?php

                    $selAdr = $c->prepare("SELECT * FROM customer_billings WHERE uid = ?");
                    $selAdr->bind_param('s', $id);
                    $selAdr->execute();
                    $sAr = $selAdr->get_result();
                    $selAdr->close();

                    if ($sAr->rowCount() < 1) {

                    ?>

                        <content-card class="posrel">
                            <div class="mshd-1 normal-box posrel">
                                <div style="padding:28px 42px;">

                                    <p class="trimfull">Keine Zahlungsmethoden hinzugefügt</p>

                                </div>
                            </div>
                        </content-card>

                    <?php

                    }

                    while ($pmm = $sAr->fetch_assoc()) {

                    ?>

                        <content-card class="half">
                            <div class="adjust mshd-1 bg-orange-purple">
                                <div class="icon">
                                    <p>
                                        <i class="material-icons em-10">credit_card</i>
                                    </p>
                                </div>
                                <div>

                                    <div class="colorfields-inr">
                                        <div class="type">
                                            <p>Kontoinhaber</p>
                                        </div>

                                        <div class="normal-field">
                                            <p><?php echo $pmm['account']; ?></p>
                                        </div>


                                        <div class="type mt12">
                                            <p>BIC</p>
                                        </div>

                                        <div class="normal-field">
                                            <p><?php echo $pmm['bic']; ?></p>
                                        </div>

                                        <div class="type mt12">
                                            <p>IBAN</p>
                                        </div>

                                        <div class="normal-field">
                                            <p>DE-<?php echo $pmm['iban']; ?></p>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </content-card>

                    <?php } // END WHILE: ADDRESSES 
                    ?>

                </div>
            </div>

        </wide-container>

<?php

    } else {
        exit;
    }
} else {
    exit;
}

?>