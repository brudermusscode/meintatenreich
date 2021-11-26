<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['pmid'])
    && $_REQUEST['action'] == 'edit-payment-method'
    && $_REQUEST['pmid'] !== ''
    && is_numeric($_REQUEST['pmid'])
    && $loggedIn
) {

    $pmid = $_REQUEST['pmid'];
    $uid  = $my->id;

    // CHECK AUTHENTICITY
    $select = $c->prepare("SELECT * FROM customer_billings WHERE id = ? AND uid = ?");
    $select->bind_param('ss', $pmid, $uid);
    $select->execute();
    $sel_r = $select->get_result();

    if ($sel_r->rowCount() > 0) {

        $s = $sel_r->fetch_assoc();
        $select->close();

        // MANDAT INFORMATION
        $selMan = $c->prepare("SELECT * FROM customer_billings_sepa WHERE pmid = ?");
        $selMan->bind_param('s', $s['id']);
        $selMan->execute();
        $selMan_r = $selMan->get_result();
        $sm = $selMan_r->fetch_assoc();
        $selMan->close();

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

                    <form data-form="edit-payment-method">

                        <input type="hidden" name="pmid" value="<?php echo $s['id']; ?>">

                        <!-- NAME -->
                        <div>
                            <div class="option w100 mr12">
                                <div class="input w100">
                                    <p>Kontoinhaber</p>
                                    <div class="actual w100">
                                        <input type="text" name="acc" placeholder="<?php echo $s['account']; ?>" class="tran-all">
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
                                            <?php echo '' . substr($s['bic'], 0, 2) . '&bull;&bull;&bull;&bull;&bull;&bull;&bull;' . substr($s['bic'], -2); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="option w50 mr12">
                                <div class="input w100">
                                    <p class="mb8">IBAN</p>
                                    <div class="actual w100 posrel">
                                        <p style="color:#C99759;">
                                            <?php echo 'Endet auf ' . substr($s['iban'], -2); ?>
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
                                            <a href="/a/sepa/<?php echo $sm['pmid']; ?>" class="fw3"><?php echo $sm['mid']; ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="mt32">
                        <div class="disfl jstfycc">
                            <button data-action="request-edit-payment-method" class="hellofresh hlf-brown rd3">Speichern</button>
                            <button data-action="delete-payment-method" data-json='[{"id":"<?php echo $s['id']; ?>", "which":"payment-method"}]' class="ml24 hellofresh hlf-white rd3" style="color:#F34236;">Löschen</button>
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
    exit;
}

$c->close();

?>