<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($elementInclude) && $admin->isAdmin()) {

    // CONVERT TIMESTAMP
    $timeAgoObject = new convertToAgo;
    $ts = $elementInclude->timestamp;
    $convertedTime = ($timeAgoObject->convert_datetime($ts));
    $when = ($timeAgoObject->makeAgo($convertedTime));

    // GET DATES
    $getCoursesDates = $pdo->prepare("
        SELECT *
        FROM courses_dates 
        WHERE cid = ? 
        AND archived != '1' 
        AND deleted != '1' 
        AND CONCAT(date, ' ', start, ':00') >= ? 
        ORDER BY CONCAT(date, ' ', start, ':00') 
        DESC
    ");
    $getCoursesDates->execute([$elementInclude->id, $elementInclude->timestamp]);

    $sdacount = $getCoursesDates->rowCount();
    $sda = $getCoursesDates->fetch();

?>



    <content-card class="mb24">
        <div class="order hd-shd adjust" style="background:url(<?php echo $url["img"]; ?>/global/bggreen.jpg) repeat;background-size:42%;">
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
                                    <li class="wic" data-action="manage:courses,edit" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>
                                        <p class="ic lt"><i class="material-icons md-18">build</i></p>
                                        <p class="lt ne trimfull">Kurs verwalten</p>

                                        <div class="cl"></div>
                                    </li>

                                    <li class="wic" data-action="manage:course,dates" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>
                                        <p class="ic lt"><i class="material-icons md-18">event_note</i></p>
                                        <p class="lt ne trimfull">Termine verwalten</p>

                                        <div class="cl"></div>
                                    </li>

                                    <div style="border-bottom:1px solid rgba(0,0,0,.08);margin-top:12px;margin-bottom:12px;"></div>

                                    <li class="wic" data-action="manage:course,toggle" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>

                                        <?php if ($elementInclude->active === '0') { ?>
                                            <p class="ic lt"><i class="material-icons md-18">blur_on</i></p>
                                            <p class="lt ne trimfull">Aktivieren</p>
                                        <?php } else { ?>
                                            <p class="ic lt"><i class="material-icons md-18">blur_off</i></p>
                                            <p class="lt ne trimfull">Deaktivieren</p>
                                        <?php } ?>

                                        <div class="cl"></div>
                                    </li>

                                    <li class="wic" data-action="manage:course,delete" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>
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
                            <?php echo $elementInclude->name; ?>
                        </p>
                    </div>

                    <div class="mt24">
                        <div class="lt mr32" style="color:rgba(0,0,0,.24);background:rgba(255,255,255,.82);height:32px;width:32px;border-radius:50%;">
                            <p class="tac course-status" style="line-height:44px;">
                                <?php if ($elementInclude->active === '1') { ?>
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
                            <p class="lt fs21"><?php echo $elementInclude->size; ?></p>

                            <div class="cl"></div>
                        </div>

                        <div class="lt lh32" style="color:rgba(255,255,255,.24);color:rgba(255,255,255,.68);">
                            <p class="lt mr4">
                                <i class="material-icons md-21 lh32">euro_symbol</i>
                            </p>
                            <p class="lt fs21"><?php echo number_format($elementInclude->price, 2, ',', '.'); ?></p>

                            <div class="cl"></div>
                        </div>

                        <div class="cl"></div>
                    </div>

                    <!-- NEXT DATE -->
                    <div class="next-date <?php if ($elementInclude->active === '0') echo 'disn'; ?>">
                        <?php

                        // GET DATES
                        $getCoursesDates = $pdo->prepare("
                            SELECT *
                            FROM courses_dates 
                            WHERE cid = ? 
                            AND archived != '1' 
                            AND deleted != '1' 
                            AND CONCAT(date, ' ', start, ':00') >= ? 
                            ORDER BY CONCAT(date, ' ', start, ':00') 
                            ASC
                            LIMIT 1
                        ");
                        $getCoursesDates->execute([$elementInclude->id, $elementInclude->timestamp]);

                        if ($getCoursesDates->rowCount() < 1) {

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

                        foreach ($getCoursesDates->fetchAll() as $sda) {

                        ?>


                            <p class="cf fw4 mt24">Nächster Termin</p>

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
                                                    $newdate = date_create($sda->date);
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
                                                    $newdate = date_create($sda->date);
                                                    echo $sda->start . ' - ' . $sda->end;

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

<?php } ?>