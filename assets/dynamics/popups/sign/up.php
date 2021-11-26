<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.config.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'open-signup') {

?>

    <login-container class="almid posabs" style="width:442px;">
        <div class="lc-inr mshd-2 rd5 zoom-in">
            <div class="title mshd-1">
                <p>Neu anmelden</p>
            </div>
            <div class="form-outer">
                <form data-form="signup">

                    <!-- NAME -->
                    <div>
                        <div class="input">
                            <div>
                                <p class="trimfull">E-Mail Adresse</p>
                            </div>
                            <div class="posrel">
                                <input type="text" name="mail" placeholder="" class="tran-all">
                                <div class="posabs" style="right:0;top:0;line-height:32px;width:32px;height:32px;text-align:center;">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div class="disfl fldirrow">
                        <div class="input w50">
                            <div class="mr12">
                                <div>
                                    <p class="trimfull">Passwort</p>
                                </div>
                                <div class="posrel">
                                    <input type="password" name="password" placeholder="" class="tran-all">
                                    <div class="posabs" style="right:0;top:0;line-height:32px;width:32px;height:32px;text-align:center;">
                                        <i class="icon-key-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input w50">
                            <div>
                                <p class="trimfull">Passwort wiederholen</p>
                            </div>
                            <div class="posrel">
                                <input type="password" name="password2" placeholder="" class="tran-all">
                                <div class="posabs" style="right:0;top:0;line-height:32px;width:32px;height:32px;text-align:center;">
                                    <i class="icon-key-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input" style="margin-bottom:0px;">
                        <div style="margin-bottom:8px;">
                            <p class="trimfull">Sind Sie ein Roboter?</p>
                        </div>
                        <div id="idcaptcha">
                            <div class="captcha">
                                <div class="g-recaptcha" data-sitekey="<?php echo $conf["recaptcha_publickey"]; ?>"></div>
                                <div class="cl"></div>
                            </div>
                        </div>
                    </div>

                    <div class="input" style="margin-top:24px;margin-bottom:0px;border-left:3px solid #F1D394;padding-left:12px;">
                        <div class="">
                            <input type="checkbox" name="agb" id="agb" value="on">
                            <label for="agb">
                                Bitte akzeptieren Sie unsere AGB und Datenschutzerklärung, bevor Sie fortfahren.
                            </label>
                        </div>
                    </div>
                </form>

            </div>

            <div class="buttons">
                <button type="button" class="rd3 hellofresh hlf-green rt" data-action="signup">Registrieren!</button>
                <div class="cl"></div>
            </div>
        </div>
    </login-container>

    <script src='https://www.google.com/recaptcha/api.js'></script>

<?php

    exit;
} else {

    exit;
}

?>