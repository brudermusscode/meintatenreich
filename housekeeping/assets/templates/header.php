<?php

$getAdminMailSettings = $pdo->prepare("SELECT * FROM admin_mails_settings WHERE id = '1'");
$getAdminMailSettings->execute();

if ($getAdminMailSettings->rowCount() > 0) {

    $mailsCh = true;
    $mailsChecked = $getAdminMailSettings->fetch();

    if ($mailsChecked->mails_checked == '0') {
        $mailsCh = false;
    }
}

?>

<style>
    .mc-heading .menu-outer ul.menu {
        list-style: none;
    }

    .mc-heading .menu-outer ul.menu li {
        line-height: 42px;
        height: 42px;
        width: 42px;
        background: rgba(255, 255, 255, .8);
        text-align: center;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
    }

    .mc-heading .menu-outer ul.menu li:hover {
        opacity: .8;
    }

    .mc-heading .menu-outer ul.menu li:active {
        opacity: .6;
    }

    .mc-heading .menu-outer ul.menu li p {
        color: #A247C0;
    }
</style>


<div class="mc-heading">
    <div class="lt left-content">

        <div class="lt">
            <div class="menu-outer">
                <ul class="menu">

                </ul>
            </div>
        </div>

        <div class="cl"></div>
    </div>

    <div class="rt right-content">
        <div class="rt">

            <div class="user-image lin-bg-purple hd-shd tran-all posrel" data-action="overview:messages,check">

                <div style="height:calc(100% - 4px);width:calc(100% - 4px);position:absolute;top:0;left:0;border-radius:20px;border:2px solid rgba(255,255,255,.32);"></div>

                <div class="pulse <?php if ($mailsCh === false) echo 'active'; ?>"></div>

                <div class="actual">
                    <p><i class="material-icons md-24 lh42">chat_bubble_outline</i></p>
                </div>
            </div>
        </div>

        <div class="cl"></div>
    </div>

    <div class="cl"></div>
</div>