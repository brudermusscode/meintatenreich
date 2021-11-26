<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add-address' && $loggedIn) {

?>

    <script>
        $('wide-container').find('input[name="fullname"]').focus();
    </script>

    <wide-container class="almid posabs">
        <div class="mshd-2 rd5 zoom-in bgf">
            <div class="hd mshd-1">
                <p class="trimfull">Adresse hinzufügen</p>
            </div>

            <div class="close tran-all" data-action="close-overlay">
                <p><i class="icon-cancel-5"></i></p>
            </div>

            <div class="body">

                <form data-form="address">

                    <!-- NAME -->
                    <div>
                        <div class="option w100 mr12">
                            <div class="input w100">
                                <p>Vollständiger Name</p>
                                <div class="actual w100">
                                    <input type="text" name="fullname" placeholder="Vor- & Nachname" class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COUNTRY -->
                    <div>
                        <div class="option w100 mr12">
                            <div class="input w100">
                                <p>Land</p>
                                <div class="actual w100">
                                    <input type="text" value="Deutschland" disabled class="tran-all">
                                    <input type="hidden" name="country" value="germany">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STREET AND HOUSE NUMBER -->
                    <div class="disfl fldirrow">
                        <div class="option w72 mr12">
                            <div class="input w100">
                                <p>Straße</p>
                                <div class="actual w100">
                                    <input type="text" name="str" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>

                        <div class="option w28 mr12">
                            <div class="input w100">
                                <p>Hausnr.</p>
                                <div class="actual w100 posrel">
                                    <input type="text" name="hnr" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EXTRA -->
                    <div>
                        <div class="option w100 mr12">
                            <div class="input w100">
                                <div>
                                    <p class="lt">Adresszusatz</p>
                                    <div style="background:rgba(0,0,0,.12);padding:0 8px;line-height:18px;" class="rd3 rt">
                                        <p style="font-size:.8em;color:#B88B56;font-weight:600;">optional</p>
                                    </div>
                                    <div class="cl"></div>
                                </div>
                                <div class="actual w100">
                                    <input type="text" name="extra" placeholder="Apartment, Einheit, Gebäude, usw." class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- POSTCODE AND CITY -->
                    <div class="disfl fldirrow">
                        <div class="option w32 mr12">
                            <div class="input w100">
                                <p>Postleitzahl</p>
                                <div class="actual w100">
                                    <input type="text" name="postcode" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>

                        <div class="option w68 mr12">
                            <div class="input w100">
                                <p>Stadt</p>
                                <div class="actual w100 posrel">
                                    <input type="text" name="city" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TELEPHONE NUMBER -->
                    <div>
                        <div class="option w100 mr12">
                            <div class="input w100">
                                <p class="lt">Telefon</p>
                                <div class="actual w100">
                                    <input type="text" name="tel" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="mt12">
                    <div class="disfl jstfycc">
                        <button data-action="request-add-address" class="hellofresh hlf-brown rd3">Adresse hinzufügen</button>
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