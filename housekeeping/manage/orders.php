<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oopsie');
}

$ptit = 'Manage: Bestellungen';
$pid = "manage:orders";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>

<script>
    $(function() {

        $.fn.circularProgress = function() {
            var DEFAULTS = {
                backgroundColor: '#b3cef6',
                progressColor: '#4b86db',
                percent: 75,
                duration: 400
            };

            $(this).each(function() {
                var $target = $(this);

                var opts = {
                    backgroundColor: $target.data('color') ? $target.data('color').split(',')[0] : DEFAULTS.backgroundColor,
                    progressColor: $target.data('color') ? $target.data('color').split(',')[1] : DEFAULTS.progressColor,
                    percent: $target.data('percent') ? $target.data('percent') : DEFAULTS.percent,
                    duration: $target.data('duration') ? $target.data('duration') : DEFAULTS.duration
                };
                // console.log(opts);

                $target.append('<div class="background"></div><div class="rotate"></div><div class="left"></div><div class="right"></div><div class=""><span>' + opts.percent + '%</span></div>');

                $target.find('.background').css('background-color', opts.backgroundColor);
                $target.find('.left').css('background-color', opts.backgroundColor);
                $target.find('.rotate').css('background-color', opts.progressColor);
                $target.find('.right').css('background-color', opts.progressColor);

                var $rotate = $target.find('.rotate');
                setTimeout(function() {
                    $rotate.css({
                        'transition': 'transform ' + opts.duration + 'ms cubic-bezier(.1,.82,.25,1)',
                        'transform': 'rotate(' + opts.percent * 3.6 + 'deg)'
                    });
                }, 1);

                if (opts.percent > 50) {
                    var animationRight = 'toggle ' + (opts.duration / opts.percent * 50) + 'ms cubic-bezier(.1,.82,.25,1)';
                    var animationLeft = 'toggle ' + (opts.duration / opts.percent * 50) + 'ms cubic-bezier(.1,.82,.25,1)';
                    $target.find('.right').css({
                        animation: animationRight,
                        opacity: 1
                    });
                    $target.find('.left').css({
                        animation: animationLeft,
                        opacity: 0
                    });
                }
            });
        }

        $(".progress-bar").circularProgress();

    });
</script>

<main-content class="overview">

    <!-- MAIN HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>

    <!-- MC: CONTENT -->
    <div class="mc-main">
        <div class="wide">

            <color-loader class="almid-h mt24 mb42">
                <inr>
                    <circl3 class="color-loader1"></circl3>
                    <circl3 class="color-loader2"></circl3>
                </inr>
            </color-loader>

            <div data-react="manage:filter"></div>

        </div>
    </div>
</main-content>

<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>