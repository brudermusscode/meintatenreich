<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (
    isset($_REQUEST['comment'], $_REQUEST['rate'])
    && $_REQUEST['comment'] !== ''
    && is_numeric($_REQUEST['rate'])
    && $loggedIn
) {

    $comment = $_REQUEST['comment'];
    $rate = $_REQUEST['rate'];

?>

    <div class="my-rate">

        <div class="stars-outer disfl fldirrow jstfycc">
            <div class="star tran-all-cubic
                                <?php if ($rate >= '1') {
                                    echo ' hit';
                                } ?>
                                ">
                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
            </div>
            <div class="star tran-all-cubic
                                <?php if ($rate >= '2') {
                                    echo ' hit';
                                } ?>
                                ">
                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
            </div>
            <div class="star tran-all-cubic
                                <?php if ($rate >= '3') {
                                    echo ' hit';
                                } ?>
                                ">
                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
            </div>
            <div class="star tran-all-cubic
                                <?php if ($rate >= '4') {
                                    echo ' hit';
                                } ?>
                                ">
                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
            </div>
            <div class="star tran-all-cubic
                                <?php if ($rate === '5') {
                                    echo ' hit';
                                } ?>
                                ">
                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
            </div>
        </div>

        <div class="cl"></div>

        <div class="comment-outer mshd-1 rd3">
            <div class="user-outer disfl fldirrow">
                <div class="uo-image">
                    <img src="<?php echo $url["img"]; ?>/elem/user.png" onload="fadeInVisOpa(this)">
                </div>
                <div class="uo-name">
                    <p class="trimfull"><?php echo $my->displayname; ?></p>
                </div>
                <div class="uo-date">
                    <p class="timeago">Jetzt</p>
                </div>
            </div>

            <div class="the-comment">
                <p><?php echo $comment; ?></p>
            </div>
        </div>

    </div>

<?php

} else {
    exit;
}

?>