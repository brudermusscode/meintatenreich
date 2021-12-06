<?php

$pid = "admin:login";
$ptit = 'Einloggen - Dashboard';

include_once $_SERVER["DOCUMENT_ROOT"] . "/maintenance/head.php";

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

                    <form data-form="login" method="POST" action>

                        <div style="margin-bottom:24px;margin-top:32px;">
                            <input type="text" class="tran-all" placeholder="E-Mail" name="mail">
                        </div>

                        <div style="margin-bottom:24px;">
                            <input type="password" class="tran-all" placeholder="Passwort" name="password">
                        </div>

                        <div class="mt32" style="border-top:1px dashed rgba(0,0,0,.42);padding-top:24px;">
                            <div class="rt">
                                <button type="submit" class="hellofresh hlf-pink rd3">
                                    Login
                                </button>
                            </div>
                        </div>

                    </form>

                    <div class="cl"></div>

                </div>
            </div>

        </div>
    </div>

    <script>
        $(function() {

            let $body = $("body");

            $('input[name="mail"], input[name="password"]').on('keypress', function(e) {
                var b = $('[data-action="signin"]');
                var i = $('[data-form="login"]');
                if (e.which == 13) {
                    b.click();
                    i.find('input').blur();
                }
            });

            // sign >> in
            $(document).on('submit', 'form[data-form="login"]', function(e) {

                e.preventDefault();

                let $loginContainer, $loginContainerOverlay, formData, method, url, overlay;

                formData = new FormData(this);
                method = $(this).attr("method");
                url = dynamicHost + "/ajax/functions/sign/in";

                if (formData.get("mail") == "" || formData.get("password") == "") {

                    showDialer("Alle Felder müssen ausgefüllt sein");
                    return false;
                } else {

                    // add new overlay
                    overlay = Overlay.add($body, true, true);
                }

                $.ajax({

                    data: formData,
                    url: url,
                    method: method,
                    contentType: false,
                    processData: false,
                    type: 'JSON',
                    success: function(data) {

                        console.log(data);

                        if (data.status) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1200);
                        } else {
                            Overlay.close($body);
                        }

                        showDialer(data.message);
                    },
                    error: function(data) {
                        console.error(data);
                    }
                });

                return false;
            });
        });
    </script>

<?php } ?>