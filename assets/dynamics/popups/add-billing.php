<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add-payment-method' && $loggedIn) {

?>

    <script>
        $('wide-container').find('input[name="acc"]').focus();
    </script>

    <wide-container class="almid posabs">
        <div class="mshd-2 rd5 zoom-in bgf">
            <div class="hd mshd-1">
                <p>Bankverbindung hinzufügen</p>
            </div>

            <div class="close tran-all" data-action="close-overlay">
                <p><i class="icon-cancel-5"></i></p>
            </div>

            <div class="body">

                <form data-form="payment-method">

                    <!-- NAME -->
                    <div>
                        <div class="option w100 mr12">
                            <div class="input w100">
                                <p>Kontoinhaber</p>
                                <div class="actual w100">
                                    <input type="text" name="acc" placeholder="Nicht Name der Bank..." class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BANKING -->
                    <div class="disfl fldirrow">
                        <div class="option w32 mr12">
                            <div class="input w100">
                                <p>BIC (Swift-Code)</p>
                                <div class="actual w100">
                                    <input type="text" name="bic" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>

                        <div class="option w68 mr12">
                            <div class="input w100">
                                <p>IBAN</p>
                                <div class="actual w100 posrel">
                                    <div style="left:0;" class="posabs almid-w">
                                        <p class="rd3" style="color:#B88B56;font-size:.8em;font-weight:500;background:rgba(0,0,0,.08);height:28px;width:auto;line-height:28px;padding:0 6px;">DE-</p>
                                    </div>
                                    <input style="padding-left:40px;width:calc(100% - 40px);" type="text" name="iban" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="mb24 p8 rd4" style="background:rgba(0,0,0,.06);">
                    <p class="fs14">Eine Mandat-Identifikationsnummer wird automatisch mit dem hinzufügen einer Bankverbindung generiert. Das Mandat kann unter "Mein Konto" eingesehen werden</p>
                </div>

                <div class="mt12">
                    <div class="disfl jstfycc">
                        <button data-action="request-add-payment-method" class="hellofresh hlf-brown rd3">Bankverbindung hinzufügen</button>
                    </div>
                </div>

            </div>
        </div>
    </wide-container>


<?php

} else {

    exit("0");
}

?>