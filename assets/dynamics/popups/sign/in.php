<?php if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'open-login') { ?>

    <script>
        $('input[name="mail"], input[name="password"]').on('keypress', function(e) {
            var b = $('[data-action="signin"]');
            var i = $('[data-form="login"]');
            if (e.which == 13) {
                b.click();
                i.find('input').blur();
            }
        });
    </script>

    <login-container class="almid posabs">

        <form id="form-login" data-form="login" method="POST" action="">

            <div class="lc-inr mshd-2 rd5 zoom-in">
                <div class="title mshd-1">
                    <p>Einloggen</p>
                </div>
                <div class="form-outer">
                    <div class="input">
                        <div>
                            <p class="trimfull">Nutzername oder E-Mail</p>
                        </div>
                        <div class="posrel">
                            <input value="justinleonseidel@gmail.com" type="text" name="mail" placeholder="" class="tran-all">
                            <div class="posabs" style="right:0;top:0;line-height:32px;width:32px;height:32px;text-align:center;">
                                <i class="icon-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="input">
                        <div>
                            <p class="trimfull">Passwort</p>
                        </div>
                        <div class="posrel">
                            <input value="..,-mond" type="password" name="password" placeholder="" class="tran-all">
                            <div class="posabs" style="right:0;top:0;line-height:32px;width:32px;height:32px;text-align:center;">
                                <i class="icon-key-1"></i>
                            </div>
                        </div>
                    </div>


                    <div class="rt trimfull">
                        <a data-action="forgot-password" href="#">
                            <p class="trimfull">Passwort vergessen?</p>
                        </a>
                    </div>

                    <div class="cl"></div>

                </div>

                <div class="buttons">
                    <button type="submit" class="rd3 hellofresh hlf-green trimfull rt">Einloggen!</button>
                    <div class="cl"></div>
                </div>

            </div>

        </form>

    </login-container>

<?php

    exit;
} else {

    exit;
}

?>