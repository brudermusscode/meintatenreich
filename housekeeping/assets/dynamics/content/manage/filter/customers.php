<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$orderValid = ['verified', 'unverified', 'all', 'done', 'canceled'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $admin->isAdmin()
) {

    $o = htmlspecialchars($_REQUEST['order']);

    switch ($o) {
        case 'all':
        default:
            $q = "SELECT * FROM customer ORDER BY timestamp DESC";
            break;
        case 'verified':
            $q = "SELECT * FROM customer WHERE verified = '1' ORDER BY timestamp DESC";
            break;
        case 'unverified':
            $q = "SELECT * FROM customer WHERE verified = '0' ORDER BY timestamp DESC";
    }

    $sel = $pdo->prepare($q);
    $sel->execute();


    if ($sel->rowCount() < 1) {

?>

        <content-card class="mb24">
            <div class="order hd-shd adjust">
                <div style="padding:82px 42px;">
                    <p class="tac">Keine Kunden zu diesem Filter</p>
                </div>
            </div>
        </content-card>

    <?php

    }

    foreach ($sel->fetchAll() as $elementInclude) {

        include $sroot . "/housekeeping/assets/dynamics/elements/customers.php";
    }

    ?>

    <div class="cl"></div>

<?php

} else {
    exit(0);
}


?>