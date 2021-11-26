<?php

include_once '../../../mysql/_.session.php';

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'open-change-password' && $loggedIn) {


?>

    <script>
        $('wide-container').find('input[name="oldpass"]').focus();
    </script>


    <wide-container class="almid posabs">
        <div class="mshd-2 rd5 zoom-in bgf">

            <div class="close tran-all" data-action="close-overlay">
                <p><i class="icon-cancel-5"></i></p>
            </div>

            <div class="body">

                <form data-form="change-password">

                    <div class="mb12">
                        <div class="option w100">
                            <div class="input w100">
                                <p>Derzeitiges Passwort</p>
                                <div class="actual w100">
                                    <input tabindex="1" name="oldpass" type="password" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>

                        <div style="padding-bottom:24px;margin-bottom:24px;margin-top:-12px;border-bottom:2px dashed rgba(0,0,0,.24);">
                            <p style="color:#999;font-size:.8em;">Falls Sie Ihr Passwort vergessen haben, beantragen Sie <a href="#">hier</a> ein neues.</p>
                        </div>

                        <div class="option w100">
                            <div class="input w100">
                                <p>Neues Passwort</p>
                                <div class="actual w100">
                                    <input tabindex="2" name="newpass" type="password" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>

                        <div class="option w100">
                            <div class="input w100">
                                <p>Neues Passwort wiederholen</p>
                                <div class="actual w100">
                                    <input tabindex="3" name="newpass2" type="password" placeholder="" class="tran-all">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                <div data-react="change-password">
                    <p style="color:red;"></p>
                </div>

                <div class="mt12">
                    <div class="disfl jstfycc">
                        <button data-action="request-password-change" class="hellofresh hlf-brown rd3">Passwort ändern</button>
                    </div>
                </div>

            </div>

            <div data-react="save-settings" class="responsive-line tran-all"></div>
        </div>
    </wide-container>


<?php

} else {

    exit;
}

?>