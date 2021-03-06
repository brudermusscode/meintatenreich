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
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/animations.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/dashbrd.main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/dashbrd.elements.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/dashbrd.products.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/dashbrd.courses.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/dashbrd.orders.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/dashbrd.messages.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/classes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/dashbrd.import.css">


    <!-- SCRIPTS -->
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/de.jq.311.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/public.suffix.list.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/tooltip/tipr.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.core.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/objects/Overlay.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.elements.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.manage.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.manage.app.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.manage.orders.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.manage.products.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.manage.customers.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.manage.courses.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.messages.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/web/js/dashbrd.mailer.min.js"></script>

    <script>
        $(function() {
            $('.alt').tipr({
                'speed': 100,
                'mode': 'below',
                'marginBelow': 6,
                'space': 12
            });
        });
    </script>

    <?php

    // switch through loading cases for all different manage pages to keep
    // a dry code and use elements only once
    switch ($pid) {
        case "manage:customers":
            $pagetitle = "customers";
            $ajaxurl = "/_magic_/ajax/content/manage/filter/customers";
            break;
        case "manage:orders":
            $pagetitle = "orders";
            $ajaxurl = "/_magic_/ajax/content/manage/filter/orders";
            break;
        case "manage:products":
            $pagetitle = "products";
            $ajaxurl = "/_magic_/ajax/content/manage/filter/products";
            break;
        case "manage:courses":
            $pagetitle = "courses";
            $ajaxurl = "/_magic_/ajax/content/manage/filter/courses";
            break;
        case "manage:ratings":
            $pagetitle = "ratings";
            $ajaxurl = "/_magic_/ajax/content/manage/filter/ratings";
            break;
        case "aindex":
            $pagetitle = "index";
            $ajaxurl = "/_magic_/ajax/content/overview/filter/all";
            break;
        case "overview:messages":
            $pagetitle = "messages";
            $ajaxurl = "/_magic_/ajax/content/overview/messages";
            break;
        default:
            $pagetitle = NULL;
            $ajaxurl = NULL;
            break;
    }

    ?>

    <script>
        $(function() {

            let react, loader, loadPageContent, $body = $("body");

            loader = $body.find('color-loader');

            loadPageContent = {
                "manage": "<?php echo $pagetitle; ?>",
                "url": dynamicHost + "<?php echo $ajaxurl; ?>"
            }


            <?php if (isset($ajaxurl) && $ajaxurl !== NULL) { ?>
                react = $body.find('[data-react="manage:filter"]');
                Manage.loadPage(loadPageContent.url, false, react, loader);
            <?php } ?>

            <?php if (isset($pid) && $pid == "overview:messages") { ?>
                react = $body.find("[data-load='overview:messages']");
                Manage.loadMessages(loadPageContent.url, false, react, loader);
            <?php } ?>

        });
    </script>

    <title><?php if (isset($ptit)) echo $ptit;
            else echo "Kein Titel"; ?> - Administration</title>

</head>

<body>

    <response-dialer id="response-dialer" class="tran-all-cubic">
        <div class="rd-head ph24 pv12">
            <div class="lt">
                <p class="icon">
                    <i class="material-icons md-18">notifications</i>
                </p>
                <p class="title">Kurse</p>
            </div>
            <div class="rt">
                <p class="close curpo" onclick="closeDialer()">
                    <i class="material-icons md-18">clear</i>
                </p>
            </div>

            <div class="cl"></div>
        </div>
        <div class="inr">
            <p class="text">abcdefg</p>
        </div>
    </response-dialer>