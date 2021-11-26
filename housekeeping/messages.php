<?php

require_once "../mysql/_.session.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oops');
    }
} else {
    header('location: /oops');
}

$ptit = 'Overview: Nachrichten';
$pid = "overview:messages";

include_once "./assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once "./assets/templates/menu.php"; ?>


<main-content class="messages">

    <!-- MC: HEADER -->
    <?php include_once "./assets/templates/header.php"; ?>


    <!-- MC: CONTENT -->
    <div class="mc-main">

        <div class="wide mb42">

            <style>
                .chooser {}

                .chooser ul.outer {
                    display: flex;
                    flex-direction: row;
                    list-style: none;
                    line-height: 42px;
                }

                .chooser ul.outer li.point {
                    height: 42px;
                    margin-right: 12px;
                    border-radius: 6px;
                    background: rgb(71, 115, 234);
                    color: white;
                    font-weight: 400;
                    padding: 0 12px;
                    cursor: pointer;
                    opacity: .6;
                }

                .chooser ul.outer li.point.green {
                    background: #4CAF50;
                }

                .chooser ul.outer li.point.active {
                    opacity: 1;
                    font-weight: 600;
                }

                .chooser ul.outer li.point:hover.active {
                    opacity: 1;
                }

                .chooser ul.outer li.point:hover {
                    opacity: .8;
                }

                .chooser ul.outer li.point p.icon {
                    height: 42px;
                }

                .msg-outer {
                    background: white;
                    border-radius: 6px;
                    border: 0px solid white;
                }

                .msg-outer.new {
                    border-left: 4px solid #2273DF;
                    background: rgb(237, 247, 255);
                }

                .msg-outer.fav {
                    border-right: 4px solid #00ac21;
                }

                .msg-outer.nofav {
                    background: rgb(244, 239, 223);
                }

                .msg-outer.read {
                    background: rgb(223, 244, 226);
                }

                .msg-outer .ui {
                    padding: 0 32px;
                    width: calc(200px);
                    height: 100%;
                }

                .msg-outer .ui .name {}

                .msg-outer .msg {
                    width: calc(100% - (200px + 2*32px + 1px));
                    border-left: 1px solid rgba(0, 0, 0, .12);
                }

                .msg-outer .msg .inr {
                    padding: 24px 32px;
                }

                .msg-outer .msg .inr .cat {}

                .msg-outer .msg .inr .cat .i {
                    height: 24px;
                    width: 24px;
                    margin-right: 12px;
                }

                .msg-outer .msg .inr .cat .i i,
                .msg-outer .msg .inr .cat .t {
                    line-height: 24px;
                }

                .msg-outer .msg .inr .cat .t {
                    color: #383838;
                    font-weight: 600;
                }

                .msg-outer .ov {
                    background: rgba(255, 255, 255, .84);
                    border-radius: 6px;
                    visibility: hidden;
                    opacity: 0;
                    z-index: 2;
                }

                .msg-outer .ov ul {
                    list-style: none;
                    height: 42px;
                }

                .msg-outer .ov ul li.p {
                    height: 42px;
                    width: 42px;
                    border-radius: 50%;
                    background: rgba(0, 0, 0, .08);
                    line-height: 42px;
                    cursor: pointer;
                    display: inline-block;
                    vertical-align: middle;
                    margin-right: 8px;
                }

                .msg-outer .ov ul li.p:last-of-type {
                    margin-right: 0px;
                }

                .msg-outer .ov ul li.p p.text,
                .msg-outer .ov ul li.p i.material-icons {
                    line-height: 42px;
                    overflow: hidden;
                }

                .msg-outer .ov ul li.p:hover {
                    margin-top: -4px;
                    background: rgba(0, 0, 0, .24);
                }

                .msg-outer .ui:hover .ov,
                .msg-outer .msg:hover .ov {
                    visibility: visible;
                    opacity: 1;
                }
            </style>

            <!-- ALL CUSTOMERS -->
            <div class="mm-heading">
                <p class="title lt lh42">Nachrichten</p>
                <div class="tools lt ml32">

                    <div class="chooser" data-element="chooser" data-action="overview:messages,panel">
                        <ul class="outer">
                            <li class="point tran-all active" data-order="got">
                                <p class="icon lt mr12">
                                    <i class="material-icons md-24 lh42">call_received</i>
                                </p>
                                <p class="text lt">Erhalten</p>

                                <div class="cl"></div>
                            </li>
                            <li class="point tran-all" data-order="sent">
                                <p class="icon lt mr12">
                                    <i class="material-icons md-24 lh42">call_made</i>
                                </p>
                                <p class="text lt">Gesendet</p>

                                <div class="cl"></div>
                            </li>
                            <li class="point tran-all green" data-order="fav">
                                <p class="icon lt mr12">
                                    <i class="material-icons md-24 lh42">star</i>
                                </p>
                                <p class="text lt">Gemerkt</p>

                                <div class="cl"></div>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="cl"></div>
            </div>

            <div data-load="overview:messages" data-order="got">
                <color-loader class="almid-h mt24 mb42">
                    <inr>
                        <circl3 class="color-loader1"></circl3>
                        <circl3 class="color-loader2"></circl3>
                    </inr>
                </color-loader>
            </div>

            <div class="cl"></div>
        </div>

    </div>
</main-content>


<?php include_once "./assets/templates/footer.php"; ?>