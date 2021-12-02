<?php

require_once "../../mysql/_.session.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oopsie');
    }
} else {
    header('location: /oopsie');
}

$ptit = 'Manage: Kunden';
$pid = "manage:customers";

include_once "../assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once "../assets/templates/menu.php"; ?>


<main-content>

    <!-- MC: HEADER -->
    <?php include_once "../assets/templates/header.php"; ?>


    <!-- MC: CONTENT -->
    <div class="mc-main">

        <div class="wide mb42">

            <!-- ALL PRODUCTS-->
            <div class="mm-heading">
                <p class="title lt lh42">Alle Kunden</p>
                <div class="tools lt ml32">
                    <div data-element="admin-select" data-action="manage:filter" data-page="customers" data-list-size="244" style="border-color:#A247C0;color:#A247C0;" class="tran-all">
                        <div class="outline disfl fldirrow">
                            <p class="text">Filtern</p>
                            <p class="icon"><i class="material-icons md-24">keyboard_arrow_down</i></p>
                        </div>

                        <datalist class="tran-all-cubic">
                            <ul>
                                <li class="trimfull" data-json='[{"order":"all"}]'>Alle anzeigen</li>
                                <li class="trimfull" data-json='[{"order":"verified"}]'>Verifiziert</li>
                                <li class="trimfull" data-json='[{"order":"unverified"}]'>Nicht verifiziert</li>
                            </ul>
                        </datalist>
                    </div>
                </div>

                <div class="cl"></div>
            </div>


            <!-- LOADER -->
            <color-loader class="almid-h mt24 mb42 disn">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-react="manage:filter">

                <?php

                // GET ALL ORDERS & USER INFORMATION
                $sel = $c->prepare("SELECT * FROM customer ORDER BY timestamp DESC");
                $sel->execute();
                $sel_r = $sel->get_result();
                $sel->close();

                while ($s = $sel_r->fetch_assoc()) {

                    $id = $s['id'];
                    $pn = mb_substr($s['firstname'], 0, 1) . mb_substr($s['secondname'], 0, 1);

                    // GET USERS ORDERS
                    $selOC = $c->prepare("SELECT * FROM customer_buys WHERE uid = ?");
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


                <?php } // END WHILE: ORDERS 
                ?>

                <div class="cl"></div>

            </div>

        </div>

    </div>
</main-content>


<?php include_once "../assets/templates/footer.php"; ?>