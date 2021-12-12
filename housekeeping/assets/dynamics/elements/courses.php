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

    <content-card class="mb24 courses <?php if ($elementInclude->active == "0") echo "deactivated";
                                        else echo "activated"; ?>" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>
        <div class="courses slideUp hd-shd adjust" style="background:#fff;">
            <div class="top" style="position:absolute;top:24px;right:32px;">

                <!-- CONTENT -->
                <div class="top-right">

                    <div class="rt status">
                        <div data-element="admin-select" data-list-align="right" data-list-size="328" class="tran-all">
                            <div class="outline">
                                <p class="icon"><i class="material-icons md-24">more_vert</i></p>
                            </div>

                            <datalist class="tran-all-cubic right">
                                <ul>

                                    <?php if ($elementInclude->deleted == '0') { ?>

                                        <li class="wic" data-action="manage:courses,edit" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>
                                            <p class="ic lt"><i class="material-icons md-18">build</i></p>
                                            <p class="lt ne trimfull">Kurs verwalten</p>

                                            <div class="cl"></div>
                                        </li>

                                        <li class="wic" data-action="manage:courses,dates" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>
                                            <p class="ic lt"><i class="material-icons md-18">event_note</i></p>
                                            <p class="lt ne trimfull">Termine verwalten</p>

                                            <div class="cl"></div>
                                        </li>

                                        <div style="border-bottom:1px solid rgba(0,0,0,.08);margin-top:12px;margin-bottom:12px;"></div>

                                        <li class="wic <?php if ($elementInclude->active == '0') echo "activate";
                                                        else echo "deactivate"; ?>" data-action="manage:courses,toggle" data-json='[{"id":"<?php echo $elementInclude->id; ?>"}]'>
                                            <p class="ic lt"><i class="material-icons md-18"></i></p>
                                            <p class="lt ne trimfull"></p>

                                            <div class="cl"></div>
                                        </li>

                                        <div style="border-bottom:1px solid rgba(0,0,0,.08);margin-top:12px;margin-bottom:12px;"></div>

                                    <?php } ?>

                                    <li class="wic <?php if ($elementInclude->deleted == '0') echo "archive";
                                                    else echo "unarchive"; ?>" data-action="manage:courses,archive">
                                        <p class="ic lt"><i class="material-icons md-18"></i></p>
                                        <p class="lt ne"></p>

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
                <div style="padding:32px 54px 24px;color:#333;">
                    <div>
                        <p class="trimfull cf" style="width:calc(100% - 42px);font-size:1.4em;line-height:1.2em;color:#333;font-weight:600;">
                            <?php echo $elementInclude->name; ?>
                        </p>
                    </div>

                    <!-- NEXT DATE -->
                    <div class="course-dates">
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

                            <content-card class="posrel" style="opacity:.6;">
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


                <div class="pv18 ph32" style="background:rgba(0,0,0,.04);">
                    <div class="lt mr32" style="background:rgba(0,0,0,.12);height:42px;width:42px;border-radius:50%;">
                        <p class="tac course-status" style="line-height:58px;">
                            <i class="material-icons md-28">trip_origin</i>
                        </p>

                        <div class="cl"></div>
                    </div>

                    <div class="info lt mr24 disfl fldirrow">
                        <p class="icon">
                            <i class="material-icons md-24">event_note</i>
                        </p>
                        <p class="text"><?php echo $sdacount; ?></p>
                    </div>

                    <div class="info lt mr24 disfl fldirrow">
                        <p class="icon">
                            <i class="material-icons md-24">person</i>
                        </p>
                        <p class="text"><?php echo $elementInclude->size; ?></p>
                    </div>

                    <div class="info lt disfl fldirrow posrel">
                        <p class="icon">
                            <i class="material-icons md-24">euro_symbol</i>
                        </p>
                        <p class="text"><?php echo number_format($elementInclude->price, 2, ',', '.'); ?></p>
                    </div>

                    <div class="cl"></div>
                </div>
            </div>
        </div>
    </content-card>

<?php } ?>