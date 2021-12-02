<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $oid = $_REQUEST['id'];

    // CHECK IF ORDER EXISTS
    $sel = $c->prepare("
            SELECT *
            FROM courses 
            WHERE courses.id = ?
            LIMIT 1
        ");
    $sel->bind_param('s', $oid);
    $sel->execute();
    $sel_r = $sel->get_result();

    if ($sel_r->rowCount() > 0) {

        // FETCH COURSE
        $o = $sel_r->fetch_assoc();
        $sel->close();


        // CONVERT TIMESTAMP
        $timeAgoObject = new convertToAgo;
        $ts = $o['timestamp'];
        $convertedTime = ($timeAgoObject->convert_datetime($ts));
        $when = ($timeAgoObject->makeAgo($convertedTime));

?>

        <wide-container style="padding-top:62px;" data-json='[{"id":"<?php echo $o['id']; ?>"}]'>


            <!-- INFORMATON BOX -->
            <div class="head-text mb12">
                <p>Termin hinzufügen</p>
            </div>


            <form data-form="manage:course,edit">

                <content-card class="mb24 posrel">
                    <div class="mshd-1 normal-box" style="background:url(<?php echo $url["img"]; ?>/global/bggreen.jpg) repeat;background-size:42%;">
                        <div style="padding:28px 42px;">


                            <div style="width:calc(50% - 12px);" class="lt">
                                <div class="fw6 mb12">
                                    <p class="cf">Datum</p>
                                </div>

                                <div class="input tran-all-cubic mb62">
                                    <div class="input-outer">
                                        <div style="color:#009688;right:18px;padding-left:12px;line-height:42px;height:32px;top:5px;font-size:1.2em;border-left:1px solid rgba(0,0,0,.12);" class="fw6 posabs">
                                            <p><i class="material-icons md-24">date_range</i></p>
                                        </div>
                                        <input type="text" autocomplete="off" name="date" placeholder="Format: JAHR-MONAT-TAG" class="tran-all" value style="padding-right:62px;width:calc(100% - 32px - 62px);">
                                    </div>
                                </div>
                            </div>


                            <style>
                                .fast-add {
                                    width: 40px;
                                    height: 40px;
                                    position: relative;
                                    margin-left: 24px;
                                }

                                .fast-add .fa-inr {
                                    height: 100%;
                                    width: 100%;
                                    line-height: 51px;
                                    height: 40px;
                                    border-radius: 50%;
                                    border: 2px solid #fff;
                                    color: #fff;
                                    cursor: pointer;
                                }

                                .fast-add .fa-inr:hover {
                                    opacity: .6;
                                }

                                .fast-add .fa-inr p {
                                    text-align: center;
                                }

                                .delete {
                                    height: 42px;
                                    width: 42px;
                                    border-radius: 50%;
                                    cursor: pointer;
                                    position: absolute;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    right: 24px;
                                }

                                .delete:hover {
                                    background: rgba(0, 0, 0, .24);
                                }

                                .delete p {
                                    text-align: center;
                                    color: #333;
                                    line-height: 53px;
                                }
                            </style>

                            <div class="fast-add rt">

                                <div class="fw6 mb12">
                                    <p style="color:#5068A1;">&nbsp;</p>
                                </div>

                                <div class="fa-inr" data-action="manage:course,dates,add">
                                    <p><i class="material-icons md-24">add</i></p>
                                </div>
                            </div>


                            <div style="width:calc(50% - 72px);" class="rt">

                                <div class="lt" style="width:calc(50% - 6px);">
                                    <div class="fw6 mb12">
                                        <p class="cf">Von</p>
                                    </div>

                                    <div class="input tran-all-cubic mb62">
                                        <div class="input-outer">
                                            <input type="text" autocomplete="off" name="start" placeholder="08:00" class="tran-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="rt" style="width:calc(50% - 6px);">
                                    <div class="fw6 mb12">
                                        <p class="cf">Bis</p>
                                    </div>

                                    <div class="input tran-all-cubic mb62">
                                        <div class="input-outer">
                                            <input type="text" autocomplete="off" name="end" placeholder="16:00" class="tran-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="cl"></div>

                            </div>

                            <div class="cl"></div>

                        </div>
                    </div>
                </content-card>

                <div data-react="manage:courses,date,add"></div>

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
                DESC 
            ");
                $sel->bind_param('ss', $oid, $timestamp);
                $sel->execute();
                $selr = $sel->get_result();
                $sel->close();

                if ($selr->rowCount() < 1) {

                ?>

                    <content-card class="mb24 posrel">
                        <div class="mshd-1 normal-box">
                            <div style="padding:42px 42px;">

                                <div class="mb12">
                                    <p style="text-align:center;">
                                        <i class="material-icons md-42">event</i>
                                    </p>
                                </div>
                                <div>
                                    <p style="font-size:18px;font-weight:400;text-align:center;" class="c8">
                                        Keine Termine für diesen Kurs
                                    </p>
                                </div>

                            </div>
                        </div>
                    </content-card>

                <?php

                }

                while ($sda = $selr->fetch_assoc()) {

                ?>

                    <content-card class="mb8 posrel">
                        <div class="mshd-1 normal-box">
                            <div style="padding:18px 42px;">

                                <div class="lt mr42">
                                    <p class="ttup fs12 c9 mb8">Datum</p>
                                    <div class="lt lh24">
                                        <p class="lt c3 mr4"><i class="material-icons md24">event</i></p>
                                        <p class="lt c3">
                                            <?php

                                            $newdate = date_create($sda['date']);
                                            echo $newdate->format('d. M y');

                                            ?>
                                        </p>

                                        <div class="cl"></div>
                                    </div>
                                </div>

                                <div class="rt">
                                    <div class="delete tran-all" data-action="manage:course,dates,delete" data-json='[{"id":"<?php echo $sda['id']; ?>"}]'>
                                        <p><i class="material-icons md-24">close</i></p>
                                    </div>
                                </div>

                                <div class="rt mr42">
                                    <div class="lt mr24">
                                        <p class="ttup fs12 c9 mb8">Start</p>
                                        <div class="lt lh24">
                                            <p class="lt c3"><?php echo $sda['start']; ?></p>

                                            <div class="cl"></div>
                                        </div>
                                    </div>

                                    <div class="lt">
                                        <p class="ttup fs12 c9 mb8">Ende</p>
                                        <div class="lt lh24">
                                            <p class="lt c3"><?php echo $sda['end']; ?></p>

                                            <div class="cl"></div>
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                                <div class="cl"></div>
                            </div>
                        </div>
                    </content-card>

                <?php } ?>

            </form>

            <div class="bottom-distance"></div>

        </wide-container>

<?php

    }
} else {
    exit;
}


?>