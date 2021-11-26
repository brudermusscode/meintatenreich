<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

if (
    isset($_REQUEST['acc'], $_REQUEST['bic'], $_REQUEST['iban'], $_REQUEST['pmid'])
    && is_numeric($_REQUEST['pmid'])
    && $loggedIn
) {

?>

    <div class="notice-papel lt" data-action="edit-payment-method" data-json='[{"uid":"<?php echo $my->id; ?>", "pmid":"<?php echo $_REQUEST['pmid']; ?>"}]'>
        <div class="needle"></div>
        <div class="np-inr">
            <div class="option mt12">
                <p class="desc ttup">Kontoinhaber</p>
                <p class="actual trimfull"><?php echo $_REQUEST['acc']; ?></p>
            </div>
            <div class="option mt12">
                <p class="desc ttup">BIC (Swift-Code)</p>
                <p class="actual trimfull"><?php echo '' . substr($_REQUEST['bic'], 0, 2) . '&bull;&bull;&bull;&bull;&bull;&bull;&bull;' . substr($_REQUEST['bic'], -2); ?></p>
            </div>
            <div class="option mt12">
                <p class="desc ttup">IBAN</p>
                <p class="actual trimfull"><?php echo 'Endet auf ' . substr($_REQUEST['iban'], -2); ?></p>
            </div>

        </div>
    </div>

<?php

} else {
    exit("0");
}

?>