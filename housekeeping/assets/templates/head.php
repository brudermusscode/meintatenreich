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
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/tooltip/tipr.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["dashbrd"]; ?>/assets/css/_gen.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["dashbrd"]; ?>/assets/css/_wide.container.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/classes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $url["css"]; ?>/animations.css">


    <!-- SCRIPTS -->
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/thirdparty/de.jq.311.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/jquery/fileupload/vendor/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/jquery/fileupload/jquery.fileupload.js"></script>
    <script type="text/javascript" src="<?php echo $url["js"]; ?>/tooltip/tipr.min.js"></script>
    <script type="text/javascript" src="<?php echo $url["dashbrd"]; ?>/assets/js/_gen.js"></script>

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