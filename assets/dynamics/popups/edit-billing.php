<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['pmid'])
    && $_REQUEST['action'] == 'edit-payment-method'
    && !empty($_REQUEST['pmid'])
    && is_numeric($_REQUEST['pmid'])
    && $loggedIn
) {

    $pmid = $_REQUEST['pmid'];
    $uid  = $my->id;

    // check if billing method belongs to user/exists
    $getBilling = $pdo->prepare("
        SELECT *, customer_billings.id AS bid, customer_billings_sepa.id AS bsid 
        FROM customer_billings, customer_billings_sepa 
        WHERE customer_billings.id = customer_billings_sepa.pmid 
        AND customer_billings.id = ? 
        AND customer_billings.uid = ?
    ");
    $getBilling->execute([$pmid, $uid]);

    if ($getBilling->rowCount() > 0) {

        $b = $getBilling->fetch();

?>

        <script>
            $('wide-container').find('input[name="acc"]').focus();
        </script>

        <wide-container class="almid posabs">
            <div class="mshd-2 rd5 zoom-in bgf">
                <div class="hd mshd-1">
                    <p>Bankverbindung bearbeiten</p>
                </div>

                <div class="close tran-all" data-action="close-overlay">
                    <p><i class="icon-cancel-5"></i></p>
                </div>

                <div class="body">

                    <form data-form="edit-payment-method" onsubmit="return false;">

                        <input type="hidden" name="pmid" value="<?php echo $b->bid; ?>">

                        <!-- NAME -->
                        <div>
                            <div class="option w100 mr12">
                                <div class="input w100">
                                    <p>Kontoinhaber</p>
                                    <div class="actual w100">
                                        <input type="text" name="acc" placeholder="<?php echo $b->account; ?>" class="tran-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BANKING -->
                        <div class="disfl fldirrow">
                            <div class="option w50 mr12">
                                <div class="input w100">
                                    <p class="mb8">BIC (Swift-Code)</p>
                                    <div class="actual w100">
                                        <p style="color:#C99759;">
                                            <?php echo '' . substr($b->bic, 0, 2) . '&bull;&bull;&bull;&bull;&bull;&bull;&bull;' . substr($b->bic, -2); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="option w50 mr12">
                                <div class="input w100">
                                    <p class="mb8">IBAN</p>
                                    <div class="actual w100 posrel">
                                        <p style="color:#C99759;">
                                            <?php echo 'Endet auf ' . substr($b->iban, -2); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="option mr12">
                                <div class="input w100">
                                    <p class="mb8">Mandat Identifikationsnnummer</p>
                                    <div class="actual w100">
                                        <p>
                                            <a href="/sepa/<?php echo $b->pmid; ?>" class="fw3"><?php echo $b->mid; ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="mt32">
                        <div class="disfl jstfycc">
                            <button data-action="request-edit-payment-method" class="hellofresh hlf-brown rd3">Speichern</button>
                            <button data-action="delete-payment-method" data-json='[{"id":"<?php echo $b->bid; ?>", "which":"billing"}]' class="ml24 hellofresh hlf-white rd3" style="color:#F34236;">Löschen</button>
                        </div>
                    </div>

                </div>
            </div>
        </wide-container>

<?php

    } else {
        exit('1');
    }
} else {
    exit("0");
}

?>