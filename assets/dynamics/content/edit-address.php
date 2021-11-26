<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['adid'])
    && $_REQUEST['action'] == 'edit-address'
    && $_REQUEST['adid'] !== ''
    && is_numeric($_REQUEST['adid'])
    && $loggedIn
) {

    $adid = $_REQUEST['adid'];
    $uid  = $my->id;

    // CHECK AUTHENTICITY
    $select = $c->prepare("SELECT * FROM customer_addresses WHERE id = ? AND uid = ?");
    $select->bind_param('ss', $adid, $uid);
    $select->execute();
    $sel_r = $select->get_result();

    if ($sel_r->rowCount() > 0) {

        $s = $sel_r->fetch_assoc();
        $select->close();

?>


        <script>
            $('wide-container').find('input[name="fullname"]').focus();
        </script>

        <wide-container class="almid posabs">
            <div class="mshd-2 rd5 zoom-in bgf">
                <div class="hd mshd-1">
                    <p>Adresse bearbeiten</p>
                </div>

                <div class="close tran-all" data-action="close-overlay">
                    <p><i class="icon-cancel-5"></i></p>
                </div>

                <div class="body">

                    <form data-form="edit-address">

                        <input type="hidden" value="<?php echo $s['id']; ?>" name="adid">

                        <!-- NAME -->
                        <div>
                            <div class="option w100 mr12">
                                <div class="input w100">
                                    <p>Vollständiger Name</p>
                                    <div class="actual w100">
                                        <input type="text" name="fullname" placeholder="<?php echo $s['fullname']; ?>" class="tran-all">
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
                        <div>
                            <div class="option w100 mr12">
                                <div class="input w100">
                                    <p>Adresse</p>
                                    <div class="actual w100">
                                        <input type="text" name="address" placeholder="<?php echo $s['address']; ?>" class="tran-all">
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
                                        <input type="text" name="extra" placeholder="<?php if ($s['additional'] === 'none') {
                                                                                            echo 'Keinen Zusatz';
                                                                                        } else {
                                                                                            echo $s['additional'];
                                                                                        } ?>" class="tran-all">
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
                                        <input type="text" name="postcode" placeholder="<?php echo $s['postcode']; ?>" class="tran-all">
                                    </div>
                                </div>
                            </div>

                            <div class="option w68 mr12">
                                <div class="input w100">
                                    <p>Stadt</p>
                                    <div class="actual w100 posrel">
                                        <input type="text" name="city" placeholder="<?php echo $s['city']; ?>" class="tran-all">
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
                                        <input type="text" name="tel" placeholder="<?php echo $s['tel']; ?>" class="tran-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="mt12">
                        <div class="disfl jstfycc">
                            <button data-action="request-edit-address" class="hellofresh hlf-brown rd3">Speichern</button>
                            <button data-action="delete-address" data-json='[{"id":"<?php echo $s['id']; ?>", "which":"address"}]' class="ml24 hellofresh hlf-white rd3" style="color:#F34236;">Löschen</button>
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