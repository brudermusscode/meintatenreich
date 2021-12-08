<?php

if (isset($_REQUEST['price'], $_REQUEST['delivery'])) {

    $pr = $_REQUEST['price'];
    $de = $_REQUEST['delivery'];

    if (is_numeric($de)) {
        $denew = ' (' . $de . 'x4 €)';
        $deac = number_format($de * 4, 2, ',', '.') . ' €';
        $sum = number_format($pr + $de * 4, 2, ',', '.');
    } else {
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
            <p style="color:#999">Versand</p>
        </div>
        <div class="rt">
            <p class="trimall" style="font-size:1.2em;color:#D7A86A;"><?php echo $deac; ?></p>
        </div>

        <div class="cl"></div>
    </div>

    <?php if ($de == "combi") { ?>
        <div style="background:#fff8e1;width:100%;" class="rd3 mt12">
            <p class="p18 cb" style="font-size:.8em;">
                Bei Kombiversand müssen die Versandkosten manuell berechnet werden. Du erhälst im Anschluss deiner Bestellung eine E-Mail mit den Versandkosten. Nach erhalt hast du die Möglichkeit, deine Bestellung binnen 12 Std. zu stornieren.
            </p>
        </div>
    <?php } ?>

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