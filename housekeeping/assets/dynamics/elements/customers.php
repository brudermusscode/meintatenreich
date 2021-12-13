<?php

if (isset($elementInclude) && $admin->isAdmin()) {

    $id = $elementInclude->id;
    $pn = mb_substr($elementInclude->firstname, 0, 1) . mb_substr($elementInclude->secondname, 0, 1);

    // GET USERS ORDERS
    $elementIncludeelOC = $pdo->prepare("SELECT * FROM customer_buys WHERE uid = ?");
    $elementIncludeelOC->execute([$id]);
    $ordersCount = $elementIncludeelOC->rowCount();

    // CONVERT TIMESTAMP
    $timeAgoObject = new convertToAgo;
    $ts = $elementInclude->timestamp;
    $convertedTime = ($timeAgoObject->convert_datetime($ts));
    $when = ($timeAgoObject->makeAgo($convertedTime));

?>

    <content-card class="lt quad">
        <div class="auser slideUp hd-shd adjust">
            <div class="user-icon">
                <div class="image-outer">
                    <div class="actual">
                        <?php if (strlen($elementInclude->firstname) > 0 && strlen($elementInclude->secondname) > 0) { ?>
                            <div class="name-letters">
                                <p><?php echo $pn; ?></p>
                            </div>
                        <?php } else { ?>
                            <img src="<?php echo $url["img"]; ?>/elem/user.png">
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="user-information">
                <div class="name">
                    <p class="trimfull">
                        <?php

                        if (strlen($elementInclude->firstname) > 0 && strlen($elementInclude->secondname) > 0) {
                            echo $elementInclude->firstname . ' ' . $elementInclude->secondname;
                        } else {
                            echo 'Kein Name';
                        }

                        ?>
                    </p>
                </div>
                <div class="extra">
                    <p class="trimfull">
                        <?php echo '@' . $elementInclude->displayname; ?>
                    </p>
                </div>
            </div>

            <div class="tools">

                <div class="tools-outer disfl fldirrow jstfycc">

                    <?php if ($elementInclude->admin === '1') { ?>
                        <div class="nopoint posrel">
                            <p style="color:#3EAF5C;" class="alt" data-tip="Administrator"><i class="material-icons md32">security</i></p>
                        </div>
                    <?php } ?>

                    <div class="nopoint posrel">
                        <?php if ($elementInclude->verified === '1') { ?>
                            <p style="color:#3EAF5C;" class="alt" data-tip="Verifizierter Kunde"><i class="material-icons md32">done</i></p>
                        <?php } else { ?>
                            <p style="color:#EA363A;" class="alt" data-tip="Nicht verifiziert"><i class="material-icons md32">clear</i></p>
                        <?php } ?>
                    </div>
                    <div class="point" data-action="mail:custom" data-json='[{"rel":"<?php echo $id; ?>", "which":"customer"}]'>
                        <p class="alt pin" data-tip="E-Mail an: <?php echo $elementInclude->mail; ?>"><i class="material-icons md32">mail</i></p>
                    </div>
                    <div class="point posrel" data-element="admin-select" data-list-size="234" data-list-align="right">
                        <p class="alt pin" data-tip="Mehr..."><i class="material-icons md32">more_vert</i></p>

                        <datalist class="tran-all-cubic right">
                            <ul>
                                <li class="wic" data-action="manage:customers,overview" data-json='[{"id":"<?php echo $id; ?>"}]'>
                                    <p class="ic lt"><i class="material-icons md-18">tab</i></p>
                                    <p class="lt ne trimfull">Übersicht öffnen</p>

                                    <div class="cl"></div>
                                </li>
                                <li class="wic" data-action="manage:customers,orders" data-json='[{"id":"<?php echo $id; ?>"}]'>
                                    <p class="ic lt"><i class="material-icons md-18">widgets</i></p>
                                    <p class="lt ne trimfull">Bestellungen</p>

                                    <div class="cl"></div>
                                </li>
                            </ul>
                        </datalist>
                    </div>
                </div>

            </div>

            <div class="stats">
                <div class="stats-outer">
                    <div class="uno">

                        <p class="title trimfull">Anmeldung</p>
                        <p class="count trimfull">
                            <?php echo $when; ?>
                        </p>

                    </div>
                    <div class="uno">

                        <p class="title trimfull">Bestellungen</p>
                        <p class="count trimfull">
                            <?php

                            if ($ordersCount === 0) {
                                echo 'Keine';
                            } else {
                                echo $ordersCount;
                            }

                            ?>
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </content-card>

<?php

} else {
    exit;
}

?>