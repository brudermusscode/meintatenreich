<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if ($admin->isAdmin()) {

    $getCourses = $pdo->prepare("SELECT * FROM courses WHERE deleted != '1' ORDER BY id DESC");
    $getCourses->execute();

    if ($getCourses->rowCount() < 1) {

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

    foreach ($getCourses->fetchAll() as $elementInclude) {

        include $sroot . "/housekeeping/assets/dynamics/elements/courses.php";
    }
} else {
    exit(0);
}

?>