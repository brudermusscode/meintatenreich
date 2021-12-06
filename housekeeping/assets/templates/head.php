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
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/tipr.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["dashbrd"]; ?>/assets/css/_gen.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["dashbrd"]; ?>/assets/css/_wide.container.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["dashbrd"]; ?>/assets/css/responsiveness.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/classes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/animations.css">


    <!-- SCRIPTS -->
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/de.jq.311.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/public.suffix.list.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/tooltip/tipr.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.core.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/objects/Overlay.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.elements.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.manage.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.manage.app.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.manage.orders.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.manage.products.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.manage.customers.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/dashbrd.messages.js"></script>

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

    <response-dialer id="response-dialer" class="tran-all-cubic mshd-3">
        <div class="inr disfl fldirrow">
            <div onclick="closeDialer()" class="icon tran-all">
                <i class="material-icons md-18 lh24">clear</i>
            </div>
            <p class="fw4"></p>
        </div>
    </response-dialer>