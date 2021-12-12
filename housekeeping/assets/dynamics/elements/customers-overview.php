<?php

if (isset($elementInclude) && $admin->isAdmin()) {

?>

    <content-card class="mb24">
        <div class="user slideUp hd-shd">

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
                    <div class="status rt" <?php if ($elementInclude->verified === '1') { ?> data-tooltip="Verifizierter Account" <?php } else { ?> data-tooltip="Nicht verifiziert" <?php } ?> data-tooltip-align="left">
                        <p class="posrel z3">
                            <?php if ($elementInclude->verified === '1') { ?>
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
                            if (strlen($elementInclude->firstname) > 0 && strlen($elementInclude->secondname) > 0) {
                                echo $elementInclude->firstname . ' ' . $elementInclude->secondname;
                            } else {
                                echo $elementInclude->displayname;
                            }

                            ?>
                        </p>
                    </div>
                    <div class="extr">
                        <p class="trimfull"><?php echo $elementInclude->mail; ?></p>
                    </div>
                </div>
            </div>

            <div class="cl"></div>
        </div>
    </content-card>

<?php

} else {
    exit;
}

?>