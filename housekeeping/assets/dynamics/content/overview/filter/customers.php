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

    foreach ($sel->fetchAll() as $elementInclude) {

        include $sroot . "/housekeeping/assets/dynamics/elements/customers-overview.php";
    }
} else {
    exit(0);
}

?>