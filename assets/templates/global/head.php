<!DOCTYPE HTML>
<html>

<head>

    <!-- NEEDED STUFF -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
    <link rel="shortcut icon" href="<?php echo $url["img"]; ?>/global/logo-green.png" type="image/png" />
    <link rel="icon" href="<?php echo $url["img"]; ?>/global/logo-green.png" type="image/png" />

    <!-- STYLING -->
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/normalize.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/gen.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/classes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/animations.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/elements.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["icons"]; ?>/fontello/css/mtr-icons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["icons"]; ?>/fontello/css/animation.css">

    <?php if ($pid === 'productview') { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/productview.css">
    <?php } ?>

    <!-- SCRIPTS -->
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/de.jq.311.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/public.suffix.list.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/core.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/shop.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/user/sign.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/user/sign.get.js"></script>

    <?php if ($pid == "contact") { ?>
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/contact.js"></script>
    <?php } else if ($pid == "productview") { ?>
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/productview.js"></script>
    <?php } else if ($pid == "scard") { ?>
        <script type="text/javascript" src="<?php echo $url["js"]; ?>/shopping-card.js"></script>
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

    <!-- NO IDEA -->
    <title><?php echo $ptit; ?> - Meintatenreich.de</title>

</head>

<body>

    <response-dialer id="response-dialer" class="tran-all-cubic mshd-3">
        <div class="inr disfl fldirrow">
            <div data-action="close-dialer" class="icon tran-all">
                <i class="icon-cancel-7"></i>
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

    <div id="app">