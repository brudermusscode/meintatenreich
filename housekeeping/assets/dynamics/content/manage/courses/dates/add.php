<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['id']) && $admin->isAdmin()) {

    $oid = $_REQUEST['id'];

    // check if course exists
    $sel = $pdo->prepare("SELECT * FROM courses WHERE courses.id = ? LIMIT 1");
    $sel->execute([$oid]);

    if ($sel->rowCount() > 0) {

        // fetch course information
        $o = $sel->fetch();

        // convert timestamp for proper readability
        $timeAgoObject = new convertToAgo;
        $ts = $o->timestamp;
        $convertedTime = ($timeAgoObject->convert_datetime($ts));
        $when = ($timeAgoObject->makeAgo($convertedTime));

?>

        <wide-container style="padding-top:62px;" data-json='[{"id":"<?php echo $o->id; ?>"}]'>


            <!-- INFORMATON BOX -->
            <div class="head-text mb12">
                <p>Termin hinzufügen</p>
            </div>


            <form data-form="manage:courses,dates" method="POST" action>

                <content-card class="mb24 posrel">
                    <div class="mshd-1 normal-box">
                        <div style="padding:28px 42px;">

                            <input type="hidden" name="id" value="<?php echo $o->id; ?>" />

                            <div style="width:calc(50% - 22px);" class="lt mr12">
                                <div class="fw6 mb12">
                                    <p>Datum</p>
                                </div>

                                <div class="input tran-all-cubic">
                                    <div class="input-outer">
                                        <input type="date" autocomplete="off" name="date" class="tran-all">
                                    </div>
                                </div>
                            </div>

                            <div style="width:calc(50% - 62px);" class="lt">

                                <div class="lt" style="width:calc(50% - 6px);">
                                    <div class="fw6 mb12">
                                        <p>Von</p>
                                    </div>

                                    <div class="input tran-all-cubic">
                                        <div class="input-outer">
                                            <input type="time" autocomplete="off" name="start" placeholder="08:00" class="tran-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="rt" style="width:calc(50% - 6px);">
                                    <div class="fw6 mb12">
                                        <p>Bis</p>
                                    </div>

                                    <div class="input tran-all-cubic">
                                        <div class="input-outer">
                                            <input type="time" autocomplete="off" name="end" placeholder="16:00" class="tran-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="cl"></div>

                            </div>

                            <div class="fast-add rt">

                                <div class="fw6 mb12">
                                    <p style="color:#5068A1;">&nbsp;</p>
                                </div>

                                <button class="fa-inr" type="submit">
                                    <p><i class="material-icons md-24">add</i></p>
                                </button>
                            </div>

                            <div class="cl"></div>

                            <div class="info-box lila fw4 mt12 hasIcon">
                                <p class="icon"><i class="material-icons md-24">help</i></p>
                                <p class="text">Klicke auf das Kalendersymbol, um ein Datum auszuwählen</p>
                            </div>

                        </div>
                    </div>
                </content-card>

                <div data-react="manage:courses,dates"></div>

                <?php

                // GET DATES
                $sel = $pdo->prepare("
                    SELECT *
                    FROM courses_dates 
                    WHERE cid = ? 
                    AND archived != '1' 
                    AND deleted != '1' 
                    AND CONCAT(date, ' ', start, ':00') >= ? 
                    ORDER BY CONCAT(date, ' ', start, ':00') 
                    DESC 
                ");
                $sel->execute([$oid, $main["fulldate"]]);

                if ($sel->rowCount() < 1) {

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

                foreach ($sel->fetchAll() as $sda) {

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

                                            $newdate = date_create($sda->date);
                                            echo $newdate->format('d. M y');

                                            ?>
                                        </p>

                                        <div class="cl"></div>
                                    </div>
                                </div>

                                <div class="rt">
                                    <div class="delete tran-all" data-action="manage:courses,dates,delete" data-json='[{"id":"<?php echo $sda->id; ?>"}]'>
                                        <p><i class="material-icons md-24">remove_circle</i></p>
                                    </div>
                                </div>

                                <div class="rt mr42">
                                    <div class="lt mr24">
                                        <p class="ttup fs12 c9 mb8">Start</p>
                                        <div class="lt lh24">
                                            <p class="lt c3"><?php echo $sda->start; ?></p>

                                            <div class="cl"></div>
                                        </div>
                                    </div>

                                    <div class="lt">
                                        <p class="ttup fs12 c9 mb8">Ende</p>
                                        <div class="lt lh24">
                                            <p class="lt c3"><?php echo $sda->end; ?></p>

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
    exit(0);
}


?>