<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oops');
}

$ptit = 'Overview: Alles';
$pid = "aindex";

include_once $sroot . "/housekeeping/assets/templates/head.php";

if (isset($_COOKIE['EzGqsVq6rY8xE5']) && $_COOKIE['EzGqsVq6rY8xE5'] === 'CjkzqEy2uhSsqc') {

?>

    <div style="height:100%;width:100%;position:fixed;top:0;left:0;">

        <div class="almid posabs" style="width:428px">

            <div style="margin-bottom:42px;">
                <p style="font-family:'Indie Flower', cursive;color:#fff;font-size:3.8em;text-align:center;text-shadow:1px 1px 10px rgba(0,0,0,.42);">MTR</p>
            </div>

            <div class="bgf mshd-2 rd5 w100">
                <div style="padding:42px 56px;">

                    <div style="display:flex;justify-content:center;">
                        <div style="background:rgba(0,0,0,.24);border-radius:50%;height:6px;width:6px;margin-right:6px;"></div>
                        <div style="background:rgba(0,0,0,.24);border-radius:50%;height:6px;width:6px;margin-right:6px;"></div>
                        <div style="background:rgba(0,0,0,.24);border-radius:50%;height:6px;width:6px;"></div>
                    </div>

                    <form data-form="login">
                        <div style="margin-bottom:24px;margin-top:32px;">
                            <input type="text" class="tran-all" placeholder="Login" name="mail">
                        </div>

                        <div style="margin-bottom:24px;">
                            <input type="password" class="tran-all" placeholder="Password" name="password">
                        </div>
                    </form>

                    <div style="margin-bottom:24px;">
                        <p style="color:#999;font-size:.8em;line-height:18px;">This login is only accessable for administrators</p>
                    </div>

                    <div class="mt32" style="border-top:1px dashed rgba(0,0,0,.42);padding-top:24px;">
                        <div class="rt">
                            <button data-action="signin" class="hellofresh hlf-pink rd3">
                                Login
                            </button>
                        </div>
                    </div>

                    <div class="cl"></div>

                </div>
            </div>

        </div>
    </div>

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

<?php } ?>