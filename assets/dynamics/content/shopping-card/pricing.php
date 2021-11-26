<?php

if (isset($_REQUEST['price'], $_REQUEST['delivery'])) {

    $pr = $_REQUEST['price'];
    $de = $_REQUEST['delivery'];

    if (is_numeric($de)) {
        $denew = ' (' . $de . 'x4 €)';
        $deac = number_format($de * 4, 2, ',', '.') . ' €';
        $sum = number_format($pr + $de * 4, 2, ',', '.');
    } else {
        $denew = ' &nbsp; <i class="icon-info-circled-1 posrel" style="cursor:pointer;color:#B88B56;" data-action="show-combi-pricing-info"></i>';
        $deac = 'ab 6 €';
        $sum = number_format($pr, 2, ',', '.') . ' +';
    }


?>

    <div class="mb6">
        <div class="lt">
            <p style="color:#999">Artikel insgesamt</p>
        </div>
        <div class="rt">
            <p class="trimall" style="font-size:1.2em;color:#D7A86A;"><?php echo number_format($pr, 2, ',', '.') . ' €'; ?></p>
        </div>

        <div class="cl"></div>
    </div>

    <div>
        <div class="lt posrel">
            <div class="opa0 vishid posabs tran-all-cubic" data-react="combi-pricing-info" style="z-index:2;right:0;bottom:24px;height:0px;overflow:hidden;width:420px;" class="rd3 mshd-2">
                <div style="background:rgba(0,0,0,.84);width:100%;" class="rd3">
                    <p class="p18 cf" style="font-size:.8em;">
                        Durch den Combiversand müssen wir die Versandkosten manuell berechnen und lassen Dir diese dann per E-Mail zukommen. Der Artikel kann bis zu 12 Stunden nach Erhalt der Versandkosten storniert werden.
                    </p>
                </div>
            </div>
            <p style="color:#999">Versand<?php echo $denew; ?></p>
        </div>
        <div class="rt">
            <p class="trimall" style="font-size:1.2em;color:#D7A86A;"><?php echo $deac; ?></p>
        </div>

        <div class="cl"></div>
    </div>

    <div class="mt12" style="border-top:1px solid rgba(0,0,0,.12);padding-top:12px">
        <div class="lt">
            <p style="color:#999" class="fw7">Gesamt</p>
        </div>
        <div class="rt">
            <p class="trimall" style="font-size:1.4em;color:#D7A86A;"><?php echo $sum . ' €'; ?></p>
        </div>

        <div class="cl"></div>
    </div>

    <script>
        $(function() {

            var doc = $(document);

            doc.on('mouseover', '[data-action="show-combi-pricing-info"]', function() {
                    var r = $('[data-react="combi-pricing-info"]');
                    var h = r.find('.rd3').height();
                    var react = r.removeClass('vishid opa0').css('height', h + 'px');
                })
                .on('mouseout', '[data-action="show-combi-pricing-info"]', function() {
                    var react = $('[data-react="combi-pricing-info"]').addClass('vishid opa0').css('height', '0px');
                });

        });
    </script>

<?php


} else {
    exit("0");
}

?>