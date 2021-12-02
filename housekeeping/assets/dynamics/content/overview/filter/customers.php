<?php

require_once "../../../../../../mysql/_.session.php";
require_once "../../../../../../mysql/_.maintenance.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oopsie');
    }
} else {
    header('location: /oopsie');
}


// GET ALL ORDERS & USER INFORMATION
$sel = $pdo->prepare("SELECT * FROM customer ORDER BY timestamp DESC LIMIT 10");
$sel->execute();
$sel_r = $sel->get_result();

while ($s = $sel_r->fetch_assoc()) {

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
                    <div class="status rt" <?php if ($s['verified'] === '1') { ?> data-tooltip="Verifizierter Account" <?php } else { ?> data-tooltip="Nicht verifiziert" <?php } ?> data-tooltip-align="left">
                        <p class="posrel z3">
                            <?php if ($s['verified'] === '1') { ?>
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
                            if (strlen($s['firstname']) > 0 && strlen($s['secondname']) > 0) {
                                echo $s['firstname'] . ' ' . $s['secondname'];
                            } else {
                                echo $s['displayname'];
                            }

                            ?>
                        </p>
                    </div>
                    <div class="extr">
                        <p class="trimfull"><?php echo $s['mail']; ?></p>
                    </div>
                </div>
            </div>

            <div class="cl"></div>
        </div>
    </content-card>

<?php

}
$sel->close(); // END WHILE: CUSTOMER

?>