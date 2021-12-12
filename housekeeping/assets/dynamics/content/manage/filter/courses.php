<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$orderValid = ["all", "archived"];

if (
    isset($orderValid, $_REQUEST["order"]) &&
    in_array($_REQUEST["order"], $orderValid) &&
    $admin->isAdmin()
) {

    $o = htmlspecialchars($_REQUEST['order']);

    switch ($o) {
        case 'all':
        default:
            $q = "SELECT * FROM courses WHERE deleted != '1' ORDER BY id DESC";
            break;
        case 'archived':
            $q = "SELECT * FROM courses WHERE deleted = '1' ORDER BY id DESC";
            break;
    }

    $sel = $pdo->prepare($q);
    $sel->execute();

    if ($sel->rowCount() < 1) {

?>

        <content-card class="mb24">
            <div class="order hd-shd adjust">
                <div style="padding:82px 42px;">
                    <p class="tac">Keine Kurse angeboten</p>
                </div>

            </div>
        </content-card>

<?php

    }

    foreach ($sel->fetchAll() as $elementInclude) {

        include $sroot . "/housekeeping/assets/dynamics/elements/courses.php";
    }
} else {
    exit(0);
}

?>