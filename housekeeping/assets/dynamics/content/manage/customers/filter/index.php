<?php

require_once "../../../../../../../mysql/_.session.php";
require_once "../../../../../../../mysql/_.maintenance.php";

$orderValid = ['verified', 'unverified', 'all', 'done', 'canceled'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $loggedIn
    && $user['admin'] === '1'
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
    $sel_r = $sel->get_result();

    if ($sel_r->rowCount() < 1) {

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

    while ($s = $sel_r->fetch_assoc()) {

        $id = $s['id'];
        $pn = mb_substr($s['firstname'], 0, 1) . mb_substr($s['secondname'], 0, 1);

        // GET USERS ORDERS
        $selOC = $pdo->prepare("SELECT * FROM customer_buys WHERE uid = ?");
        $selOC->bind_param('s', $id);
        $selOC->execute();
        $selOC_r = $selOC->get_result();
        $selOC->close();
        $ordersCount = $selOC_r->rowCount();

        // CONVERT TIMESTAMP
        $timeAgoObject = new convertToAgo;
        $ts = $s['timestamp'];
        $convertedTime = ($timeAgoObject->convert_datetime($ts));
        $when = ($timeAgoObject->makeAgo($convertedTime));

    ?>

        <content-card class="lt tripple">
            <div class="auser hd-shd adjust">
                <div class="user-icon">
                    <div class="image-outer">
                        <div class="actual">
                            <?php if (strlen($s['firstname']) > 0 && strlen($s['secondname']) > 0) { ?>
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

                            if (strlen($s['firstname']) > 0 && strlen($s['secondname']) > 0) {
                                echo $s['firstname'] . ' ' . $s['secondname'];
                            } else {
                                echo 'Kein Name';
                            }

                            ?>
                        </p>
                    </div>
                    <div class="extra">
                        <p class="trimfull">
                            <?php echo '@' . $s['displayname']; ?>
                        </p>
                    </div>
                </div>

                <div class="tools">

                    <div class="tools-outer disfl fldirrow jstfycc">

                        <?php if ($s['admin'] === '1') { ?>
                            <div class="nopoint posrel">
                                <p style="color:#3EAF5C;" class="alt" data-tip="Administrator"><i class="material-icons md32">security</i></p>
                            </div>
                        <?php } ?>

                        <div class="nopoint posrel">
                            <?php if ($s['verified'] === '1') { ?>
                                <p style="color:#3EAF5C;" class="alt" data-tip="Verifizierter Kunde"><i class="material-icons md32">done</i></p>
                            <?php } else { ?>
                                <p style="color:#EA363A;" class="alt" data-tip="Nicht verifiziert"><i class="material-icons md32">clear</i></p>
                            <?php } ?>
                        </div>
                        <div class="point" data-action="mail:custom" data-json='[{"rel":"<?php echo $id; ?>", "which":"customer"}]'>
                            <p class="alt pin" data-tip="E-Mail an: <?php echo $s['mail']; ?>"><i class="material-icons md32">mail</i></p>
                        </div>
                        <div class="point posrel" data-element="admin-select" data-list-size="234" data-list-align="right">
                            <p class="alt pin" data-tip="Mehr..."><i class="material-icons md32">more_vert</i></p>

                            <datalist class="tran-all-cubic">
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

    } // END WHILE

    ?>

    <div class="cl"></div>

<?php

} else {
    exit;
}


?>