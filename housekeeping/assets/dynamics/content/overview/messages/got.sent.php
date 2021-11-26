<?php


require_once "../../../../../../mysql/_.session.php";


$orderValid = ['got', 'sent', 'fav'];

if (
    isset($_REQUEST['order'])
    && in_array($_REQUEST['order'], $orderValid)
    && $loggedIn
    && $user['admin'] === '1'
) {

    $order = $_REQUEST['order'];

    if ($order === 'got') {
        $query = "SELECT * FROM admin_mails_got ORDER BY isread ASC, timestamp DESC";
    } else if ($order === 'sent') {
        $query = "SELECT * FROM admin_mails_sent ORDER BY timestamp DESC";
    } else {
        $query = "SELECT * FROM admin_mails_got WHERE fav = '1' ORDER BY isread ASC, timestamp DESC";
    }

    $sel = $c->prepare($query);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    while ($s = $sr->fetch_assoc()) {

        $id = $s['id'];

        // GET CAT
        if ($order === 'got' || $order === 'fav') {

            $cid = $s['cid'];
            $selCat = $c->prepare("SELECT * FROM admin_mails_categories WHERE id = ?");
            $selCat->bind_param('s', $cid);
            $selCat->execute();
            $scr = $selCat->get_result();
            $sc = $scr->fetch_assoc();
            $selCat->close();

            $fullname = $s['fullname'];
            $ref = $s['ref'];
            $overlay = true;
            $category = $sc['name'];
        } else {

            $sid = $s['sid'];
            if ($s['uref'] === 'none') {
                $uid = 'Rundmail';
            } else {
                $uid = $s['uref'];
            }

            // GET USER INFORMATION: Sent
            $selUs = $c->prepare("SELECT * FROM customer WHERE id = ?");
            $selUs->bind_param('s', $sid);
            $selUs->execute();
            $sus = $selUs->get_result();
            $su = $sus->fetch_assoc();
            $selUs->close();

            // GET USER INFORMATION: Got
            if (is_numeric($uid)) {
                $selUsGot = $c->prepare("SELECT * FROM customer WHERE id = ?");
                $selUsGot->bind_param('s', $uid);
                $selUsGot->execute();
                $susg = $selUsGot->get_result();
                $sug = $susg->fetch_assoc();
                $selUsGot->close();

                $mailto = $sug['mail'];
            } else {
                $mailto = $uid;
            }

            $fullname = $su['firstname'] . ' ' . $su['secondname'];
            $ref = $su['mail'];
            $overlay = false;
            $category = 'Direkt Mail';
        }

        // CONVERT TIMESTAMP
        $timeAgoObject = new convertToAgo;
        $ts = $s['timestamp'];
        $convertedTime = ($timeAgoObject->convert_datetime($ts));
        $when = ($timeAgoObject->makeAgo($convertedTime));

?>

        <content-card class="mb12 posrel" data-json='[{"id":"<?php echo $id; ?>"}]'>


            <div class="msg-outer mshd-1 posrel <?php if ($s['isread'] === '0') echo 'new'; ?> <?php if ($s['fav'] === '1') echo 'fav'; ?> tran-all-cubic" data-react="overview:messages">

                <div class="ui lt posabs w100 h100 l0 t0">

                    <?php if ($overlay === true) { ?>
                        <div class="ov us posabs t0 l0 w100 h100 tran-all">
                            <ul class="ov-inr almid posabs">
                                <li class="p tac tran-all-cubic posrel" data-tooltip="E-Mail senden" data-tooltip-align="bottom" data-action="mail:custom" data-json='[{"which":"msg", "rel":"<?php echo $ref; ?>"}]'>
                                    <p class="text">
                                        <i class="material-icons md24">mail</i>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>

                    <div class="posabs almid-w">
                        <div class="name">
                            <p class="fw6 trimfull w100 c3">
                                <?php echo $fullname; ?>
                            </p>
                        </div>
                        <div class="mail">
                            <p class="fw4 trimfull w100"><?php echo $ref; ?></p>
                        </div>
                    </div>

                </div>

                <div class="msg rt posrel">

                    <div class="ov ms posabs t0 l0 w100 h100 tran-all">
                        <ul class="ov-inr almid posabs">

                            <form data-form="overview:messages,actions">

                                <li class="p tac tran-all-cubic" data-action="overview:messages,open" data-tooltip="Öffnen" data-tooltip-align="bottom">
                                    <p class="text"><i class="material-icons md24">launch</i></p>
                                </li>


                                <?php if ($overlay === true) { ?>
                                    <!-- read // not read -->
                                    <li class="p tac tran-all-cubic" data-action="overview:messages,read" data-tooltip="<?php if ($s['isread'] === '0') echo 'Als gelesen markieren';
                                                                                                                        else echo 'Als ungelesen markieren'; ?>" data-tooltip-align="bottom">
                                        <p class="text">
                                            <i class="material-icons md24"><?php if ($s['isread'] === '0') echo 'done';
                                                                            else echo 'unsubscribe'; ?></i>
                                        </p>
                                    </li>

                                    <input type="hidden" name="isread" value="<?php echo $s['isread']; ?>">

                                    <!-- Fav o not fav -->
                                    <li class="p tac tran-all-cubic" data-action="overview:messages,fav" data-tooltip="<?php if ($s['fav'] === '0') echo 'Merken';
                                                                                                                        else echo 'Nicht mehr merken'; ?>" data-tooltip-align="bottom">
                                        <p class="text">
                                            <i class="material-icons md24"><?php if ($s['fav'] === '0') echo 'star_border';
                                                                            else echo 'star'; ?></i>
                                        </p>
                                    </li>

                                    <input type="hidden" name="fav" value="<?php echo $s['fav']; ?>">
                                <?php } ?>

                            </form>

                        </ul>
                    </div>


                    <div class="inr">
                        <div class="cat">
                            <?php if ($overlay === false) { ?>
                                <div class="i lt">
                                    <p><i class="material-icons md-24">mail</i></p>
                                </div>
                                <div class="t lt">
                                    <p><span class="fw4">An:</span> <?php echo $mailto; ?></p>
                                </div>
                            <?php } else { ?>
                                <div class="i lt">
                                    <p><i class="material-icons md-24">all_inclusive</i></p>
                                </div>
                                <div class="t lt">
                                    <p><?php echo $category; ?></p>
                                </div>
                            <?php } ?>

                            <div class="cl"></div>
                        </div>
                        <div class="posrel mt8" style="line-height:1.3;">
                            <p class="trimfull w100 veralmid c3" style="height:24px;" data-react="overview:messages,open,fulltext">
                                <?php echo $s['msg']; ?>
                            </p>
                        </div>
                        <div class="timestamp mt8">
                            <p class="c7 tar trimfull w100 veralmid"><?php echo $when; ?></p>
                        </div>
                    </div>
                </div>

                <div class="cl"></div>
            </div>

        </content-card>

<?php

    } // END WHILE: MSGS

} else {
    exit;
}
