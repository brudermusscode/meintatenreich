<!--
           _
       .__(.)< (MEOW)
        \___)   
 ~~~~~~~~~~~~~~~~~~-->

<!DOCTYPE HTML>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />

    <?php if ($pid !== "maintenance") { ?>

        <link rel="shortcut icon" href="<?php echo $url["img"]; ?>/global/logo-green.png" type="image/png" />
        <link rel="icon" href="<?php echo $url["img"]; ?>/global/logo-green.png" type="image/png" />

        <!-- beauti -->
        <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/import.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $url["main"]; ?>/assets/web/fonts/fontello/css/fontello.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $url["main"]; ?>/assets/web/fonts/fontello/css/animation.css">

        <!-- hacks -->
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/de.jq.311.js"></script>
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/public.suffix.list.js"></script>
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/core.min.js"></script>
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/shop.min.js"></script>
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/user/sign.min.js"></script>
        <?php if ($loggedIn) { ?>
            <script type="text/javascript" src="<?php echo $url["js"]; ?>/user/user.min.js"></script>
        <?php } ?>

        <?php if ($pid == "contact") { ?>
            <script type="text/javascript" src="<?php echo $url["js"]; ?>/contact.min.js"></script>
        <?php } else if ($pid == "productview") { ?>
            <script type="text/javascript" src="<?php echo $url["js"]; ?>/productview.min.js"></script>
        <?php } else if ($pid == "scard") { ?>
            <script type="text/javascript" src="<?php echo $url["js"]; ?>/shopping-card.min.js"></script>
        <?php } ?>

        <?php if (!isset($_COOKIE['cookies'])) { ?>
            <script type="text/javascript">
                setTimeout(function() {
                    $('cookie-accept').css('bottom', '12px');
                }, 400);
                $(document).on('click', '[data-action="accept-cookies"]', function() {
                    var t = $(this);
                    var data = t.data('json');
                    data = data[0].set;
                    if (data === '1') {
                        setCookie('cookies', 'true', 20 * 365);
                    } else {
                        setCookie('cookies', 'false', 20 * 365);
                    }
                    $('cookie-accept').css('bottom', '-100px');
                    setTimeout(function() {
                        $('cookie-accept').remove();
                    }, 400);
                });
            </script>
        <?php } ?>

    <?php } else { ?>



    <?php } ?>

    <title><?php echo $ptit; ?> - Meintatenreich.de</title>

</head>

<body>

    <response-dialer id="response-dialer" class="tran-all-cubic mshd-3">
        <div class="inr disfl fldirrow">
            <div data-action="close-dialer" class="icon tran-all">
                <i class="icon-down-open"></i>
            </div>
            <p></p>
        </div>
    </response-dialer>

    <?php if (!isset($_COOKIE['cookies'])) { ?>

        <cookie-accept class="mshd-2 rd5 tran-all-cubic">
            <div class="inr">
                <p>
                    Bitte bestätige, dass wir <strong><a href="#">Cookies</a></strong> setzen dürfen.
                    <button class="hellofresh hlf-brown-s rd3 ml12" type="button" data-action="accept-cookies" data-json='[{"set":"1"}]'>Akzeptieren</button>
                    <button class="hellofresh hlf-white-s rd3 ml4" type="button" data-action="accept-cookies" data-json='[{"set":"0"}]'>Ablehnen</button>
                </p>
            </div>
        </cookie-accept>

    <?php } ?>

    <style>
        [data-element="dumper"] {
            left: -250px;
            background: rgba(255, 255, 255, .9);
            border-radius: 0px;
            top: 0;
            position: fixed;
            z-index: 9999999999999;
            width: 300px;
            height: 100vh;
            transition: all .1s ease-out;
        }

        [data-element="dumper"]:hover {
            left: 0;
        }

        [data-element="dumper"] .title {
            font-size: 1.2em;
        }
    </style>

    <?php if ($admin->isAdmin()) { ?>

        <div data-element="dumper" class="mshd-4">
            <div style="padding:12px;font-size:.6em;color:rgba(51,51,51);">
                <p class="title"><strong>SESSION dump</strong></p>
                <pre class="ovhid"><?php var_dump($admin->getDump("session")); ?></pre>
                <p class="mt24 title"><strong>REQUEST dump</strong></p>
                <pre class="ovhid"><?php var_dump($admin->getDump("request")); ?></pre>
            </div>
        </div>

    <?php } ?>

    <div id="app">