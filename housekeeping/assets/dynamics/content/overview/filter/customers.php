<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST["order"]) && $admin->isAdmin()) {

    // get customers
    $sel = $pdo->prepare("SELECT * FROM customer ORDER BY timestamp DESC LIMIT 10");
    $sel->execute();

    if ($sel->rowCount() < 1) {

?>

        <content-card class="mb24" style="margin-bottom:200px;">
            <div class="order hd-shd adjust">
                <div style="padding:82px 42px;">
                    <p class="tac">Hier gibt es noch nichts zu sehen! ;)</p>
                </div>

            </div>
        </content-card>

    <?php

        exit;
    }

    foreach ($sel->fetchAll() as $s) {

    ?>

        <content-card class="mb24">
            <div class="user hd-shd">

                <!-- USER ICON -->
                <div class="user-icon">
                    <div class="actual">
                        <div class="img-outer">
                            <div class="img"></div>
                        </div>
                    </div>
                </div>

                <div class="user-content rt">
                    <div class="top">
                        <div class="type lt">
                            <p>neuer kunde</p>
                        </div>
                        <div class="status rt" <?php if ($s->verified === '1') { ?> data-tooltip="Verifizierter Account" <?php } else { ?> data-tooltip="Nicht verifiziert" <?php } ?> data-tooltip-align="left">
                            <p class="posrel z3">
                                <?php if ($s->verified === '1') { ?>
                                    <i class="material-icons md-28 v">verified_user</i>
                                <?php } else { ?>
                                    <i class="material-icons md-28 n">verified_user</i>
                                <?php } ?>
                            </p>
                        </div>

                        <div class="cl"></div>
                    </div>

                    <div class="middle">
                        <div class="name">
                            <p class="trimfull">
                                <?php

                                // CHECK CUSTOMER NAME
                                if (strlen($s->firstname) > 0 && strlen($s->secondname) > 0) {
                                    echo $s->firstname . ' ' . $s->secondname;
                                } else {
                                    echo $s->displayname;
                                }

                                ?>
                            </p>
                        </div>
                        <div class="extr">
                            <p class="trimfull"><?php echo $s->mail; ?></p>
                        </div>
                    </div>
                </div>

                <div class="cl"></div>
            </div>
        </content-card>

<?php

    }
} else {
    exit(0);
}

?>