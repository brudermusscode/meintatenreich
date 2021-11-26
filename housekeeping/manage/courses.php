<?php

require_once "../../mysql/_.session.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oopsie');
    }
} else {
    header('location: /oopsie');
}

$ptit = 'Manage: Kurse';
$pid = "manage:courses";

include_once "../assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once "../assets/templates/menu.php"; ?>

<main-content>

    <!-- MC: HEADER -->
    <?php include_once "../assets/templates/header.php"; ?>


    <!-- MC: CONTENT -->
    <div class="mc-main">
        <div class="wide">



            <!-- ATTENTION -->
            <div class="mm-heading mb12">
                <p class="title lt lh42">Kurse</p>

                <div class="rt">
                    <div class="mshd-1" style="color:#A247C0;border-radius:50px;background:white;cursor:pointer;padding:0 18px;" data-action="manage:course,add">
                        <p class="lt mr12"><i class="material-icons md-24 lh42">add</i></p>
                        <p class="lt lh42">Kurs hinzufügen</p>

                        <div class="cl"></div>
                    </div>
                </div>

                <div class="cl"></div>
            </div>


            <style>
                .green-gradient {}
            </style>


            <div data-react="manage:filter">

                <?php

                // GET ALL ORDERS & USER INFORMATION
                $sel = $c->prepare("
                            SELECT *
                            FROM courses 
                            WHERE deleted != '1' 
                            ORDER BY id
                            DESC
                        ");
                $sel->execute();
                $sel_r = $sel->get_result();
                $sel->close();

                if ($sel_r->rowCount() < 1) {

                ?>

                    <content-card class="mb24">
                        <div class="order hd-shd adjust">
                            <div style="padding:82px 42px;">
                                <p class="tac">Keine Kurse angeboten</p>
                            </div>

                        </div>
                    </content-card>

                <?php

                } // END IF EMPTY

                while ($s = $sel_r->fetch_assoc()) {

                    // CONVERT TIMESTAMP
                    $timeAgoObject = new convertToAgo;
                    $ts = $s['timestamp'];
                    $convertedTime = ($timeAgoObject->convert_datetime($ts));
                    $when = ($timeAgoObject->makeAgo($convertedTime));

                    // GET DATES
                    $sel = $c->prepare("
                                SELECT *
                                FROM courses_dates 
                                WHERE couid = ? 
                                AND archived != '1' 
                                AND deleted != '1' 
                                AND CONCAT(date, ' ', start, ':00') >= ? 
                                ORDER BY CONCAT(date, ' ', start, ':00') 
                                DESC
                            ");
                    $sel->bind_param('ss', $s['id'], $timestamp);
                    $sel->execute();
                    $selr = $sel->get_result();
                    $sel->close();

                    $sdacount = $selr->rowCount();
                    $sda = $selr->fetch_assoc();

                ?>



                    <content-card class="mb24">
                        <div class="order hd-shd adjust" style="background:url(<?php echo $imgurl; ?>/global/bggreen.jpg) repeat;background-size:42%;">
                            <div class="top" style="position:absolute;top:24px;right:32px;">

                                <!-- CONTENT -->
                                <div class="top-right">

                                    <div class="rt status">
                                        <div data-element="admin-select" data-list-align="right" data-list-size="328" style="height:42px;width:42px;position:relative;overflow:hdden;" class="tran-all">
                                            <div class="outline disfl fldirrow" style="border:0;width:100%;height:100%;padding:0;margin:0;">
                                                <p class="icon cf"><i class="material-icons md-24 lh42">more_vert</i></p>
                                            </div>

                                            <datalist class="tran-all-cubic">
                                                <ul>
                                                    <li class="wic" data-action="manage:course" data-json='[{"id":"<?php echo $s['id']; ?>"}]'>
                                                        <p class="ic lt"><i class="material-icons md-18">build</i></p>
                                                        <p class="lt ne trimfull">Kurs verwalten</p>

                                                        <div class="cl"></div>
                                                    </li>

                                                    <li class="wic" data-action="manage:course,dates" data-json='[{"id":"<?php echo $s['id']; ?>"}]'>
                                                        <p class="ic lt"><i class="material-icons md-18">event_note</i></p>
                                                        <p class="lt ne trimfull">Termine verwalten</p>

                                                        <div class="cl"></div>
                                                    </li>

                                                    <div style="border-bottom:1px solid rgba(0,0,0,.08);margin-top:12px;margin-bottom:12px;"></div>

                                                    <li class="wic" data-action="manage:course,toggle" data-json='[{"id":"<?php echo $s['id']; ?>"}]'>

                                                        <?php if ($s['active'] === '0') { ?>
                                                            <p class="ic lt"><i class="material-icons md-18">blur_on</i></p>
                                                            <p class="lt ne trimfull">Aktivieren</p>
                                                        <?php } else { ?>
                                                            <p class="ic lt"><i class="material-icons md-18">blur_off</i></p>
                                                            <p class="lt ne trimfull">Deaktivieren</p>
                                                        <?php } ?>

                                                        <div class="cl"></div>
                                                    </li>

                                                    <li class="wic" data-action="manage:course,delete" data-json='[{"id":"<?php echo $s['id']; ?>"}]'>
                                                        <p class="ic lt"><i class="material-icons md-18">close</i></p>
                                                        <p class="lt ne trimfull">Löschen</p>

                                                        <div class="cl"></div>
                                                    </li>

                                                </ul>
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="cl"></div>

                                </div>

                            </div>

                            <div class="course-content">
                                <div style="padding:32px 54px;">
                                    <div>
                                        <p class="trimfull cf" style="width:calc(100% - 42px);font-size:1.4em;line-height:1.2em;text-shadow:1px 1px 1px rgba(0,0,0,.32);">
                                            <?php echo $s['name']; ?>
                                        </p>
                                    </div>

                                    <div class="mt24">
                                        <div class="lt mr32" style="color:rgba(0,0,0,.24);background:rgba(255,255,255,.82);height:32px;width:32px;border-radius:50%;">
                                            <p class="tac course-status" style="line-height:44px;">
                                                <?php if ($s['active'] === '1') { ?>
                                                    <i class="material-icons md-21 cgreen">trip_origin</i>
                                                <?php } else { ?>
                                                    <i class="material-icons md-21 cred">trip_origin</i>
                                                <?php } ?>
                                            </p>

                                            <div class="cl"></div>
                                        </div>

                                        <div class="lt mr24 lh32" style="color:rgba(255,255,255,.68);">
                                            <p class="lt mr4">
                                                <i class="material-icons md-21 lh32">event_note</i>
                                            </p>
                                            <p class="lt fs21"><?php echo $sdacount; ?></p>

                                            <div class="cl"></div>
                                        </div>

                                        <div class="lt mr24 lh32" style="color:rgba(0,0,0,.24);color:rgba(255,255,255,.68);">
                                            <p class="lt mr4">
                                                <i class="material-icons md-21 lh32">person</i>
                                            </p>
                                            <p class="lt fs21"><?php echo $s['size']; ?></p>

                                            <div class="cl"></div>
                                        </div>

                                        <div class="lt lh32" style="color:rgba(255,255,255,.24);color:rgba(255,255,255,.68);">
                                            <p class="lt mr4">
                                                <i class="material-icons md-21 lh32">euro_symbol</i>
                                            </p>
                                            <p class="lt fs21"><?php echo number_format($s['price'], 2, ',', '.'); ?></p>

                                            <div class="cl"></div>
                                        </div>

                                        <div class="cl"></div>
                                    </div>

                                    <!-- NEXT DATE -->
                                    <div class="next-date <?php if ($s['active'] === '0') echo 'disn'; ?>">
                                        <?php

                                        // GET DATES
                                        $sel = $c->prepare("
                                            SELECT *
                                            FROM courses_dates 
                                            WHERE couid = ? 
                                            AND archived != '1' 
                                            AND deleted != '1' 
                                            AND CONCAT(date, ' ', start, ':00') >= ? 
                                            ORDER BY CONCAT(date, ' ', start, ':00') 
                                            ASC
                                            LIMIT 1
                                        ");
                                        $sel->bind_param('ss', $s['id'], $timestamp);
                                        $sel->execute();
                                        $selr = $sel->get_result();
                                        $sel->close();

                                        if ($selr->rowCount() < 1) {

                                        ?>

                                            <content-card class="mt24 posrel" style="opacity:.6;">
                                                <div class="mshd-1 normal-box">
                                                    <div style="padding:24px 12px;">

                                                        <div>
                                                            <p style="font-size:18px;font-weight:400;text-align:center;" class="c8">
                                                                Keine Termine
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </content-card>

                                        <?php

                                        }

                                        while ($sda = $selr->fetch_assoc()) {

                                        ?>


                                            <p style="" class="cf fw4 mt24">Nächster Termin</p>

                                            <content-card class="mt8 posrel">
                                                <div class="mshd-1 normal-box">
                                                    <div style="padding:18px 36px;">

                                                        <div>
                                                            <div class="lh24 lt">
                                                                <p class="ttup fs12 c9 mb2">Datum</p>
                                                                <p class="lt c3 mr4"><i class="material-icons md24">event</i></p>
                                                                <p class="lt c3">
                                                                    <?php

                                                                    //$newLocale = setlocale(LC_TIME, 'de_DE', 'de_DE.UTF-8');
                                                                    $newdate = date_create($sda['date']);
                                                                    echo $newdate->format('d. M y');

                                                                    ?>
                                                                </p>

                                                                <div class="cl"></div>
                                                            </div>

                                                            <div class="lh24 rt">
                                                                <p class="ttup fs12 c9 mb2">Zeit</p>
                                                                <p class="lt c3 mr4"><i class="material-icons md24">watch_later</i></p>
                                                                <p class="lt c3">
                                                                    <?php

                                                                    //$newLocale = setlocale(LC_TIME, 'de_DE', 'de_DE.UTF-8');
                                                                    $newdate = date_create($sda['date']);
                                                                    echo $sda['start'] . ' - ' . $sda['end'];

                                                                    ?>
                                                                </p>

                                                                <div class="cl"></div>
                                                            </div>

                                                            <div class="cl"></div>

                                                        </div>

                                                        <div class="cl"></div>
                                                    </div>
                                                </div>
                                            </content-card>

                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </content-card>



                <?php

                }

                ?>

            </div>

        </div>
    </div>
</main-content>