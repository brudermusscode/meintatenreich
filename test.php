<?php

require_once "./mysql/_.prepare.php";

$ptit = "We are under maintenance";
$pid = "maintenance";

include_once "./assets/templates/global/head.php";

?>


<style>
    * {
        font-family: Arial, sans-serif;
    }

    body {
        overflow: auto;
        overflow-x: hidden;
    }

    strong {
        font-weight: bold;
    }

    input {
        border: 0;
        border-bottom: 1px solid rgba(0, 0, 0, .24);
        line-height: 42px;
        height: 42px;
        width: 100%;
        padding: 0;
        outline: none;
    }

    input:focus,
    input:hover {
        border-bottom: 1px solid #2195F2;
    }
</style>

<?php if (isset($_COOKIE['EzGqsVq6rY8xE5']) && $_COOKIE['EzGqsVq6rY8xE5'] === 'CjkzqEy2uhSsqc') { ?>

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
                        <p style="color:#999;font-size:.8em;line-height:18px;">This login is only accessable for administrators of this web presence.</p>
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

<?php } else { ?>

    <style>
        #app {
            width: auto;
        }

        .mmain {
            margin: 0 auto;
            margin-top: 78px;
            margin-bottom: 62px;
            width: 690px;
        }

        .mmain .logo-outer {
            position: relative;
            width: 262px;
            height: 127px;
            margin-bottom: 52px;
        }

        .mmain .logo-outer .actual {
            height: 100%;
            width: 100%;
        }

        .mmain .mm-box {
            margin-bottom: 38px;
            width: 100%;
        }

        .mmain .mm-box.shops {
            float: none;
            width: 100%;
        }

        .mmain .mm-box .mmb-inr .hd {
            line-height: 21px;
            margin-bottom: 18px;
        }

        .mmain .mm-box .mmb-inr .hd p {
            font-size: 1.8em;
            font-family: 'Indie Flower', sans-serif;
            text-align: left;
            padding-left: 24px;
            text-shadow: 0 1px 1px white;
        }

        .mmain .mm-box .mmb-inr .body {
            line-height: 21px;
        }

        .mmain .mm-box .mmb-inr .body p {
            color: #333;
            font-size: 1em;
        }

        @media screen and (max-width:calc(690px + 48px)) {
            .mmain {
                width: calc(100% - 48px);
            }
        }

        .button-outer {
            position: relative;
        }

        .button-outer .button {
            width: calc(50% - 3px);
            background: #BA8F5E;
            cursor: pointer;
        }

        .button-outer .button:hover {
            opacity: .8;
        }

        .button-outer .button:active {
            opacity: .6;
        }

        .button-outer .button p {
            color: white !important;
            text-align: center;
            line-height: 42px;
            font-size: 1.2em !important;
            font-weight: 300 !important;
            padding: 0 12px;
            width: calc(100% - 24px);
        }

        .button-outer .button:nth-of-type(2) {
            margin-left: 6px;
        }

        .dot {
            background: rgba(255, 255, 255, .84);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .42);
            border-radius: 50%;
            height: 6px;
            width: 6px;
        }
    </style>

    <div class="mmain">
        <div class="logo-outer almid-h">
            <div class="actual" style="background:url(<?php echo $imgurl; ?>/global/logo-font.svg) center no-repeat;background-size:cover;"></div>
        </div>

        <div class="mm-box">
            <div class="mmb-inr">
                <div class="hd">
                    <p>Hier wird umgebaut!</p>
                </div>
                <div class="body bgf rd3 mshd-1 p32">
                    <p>Herzlich Willkommen auf <strong>MeinTatenreich</strong>. Leider ist mein Shop in einer Umbauphase und deshalb bitte ich Dich, etwas Geduld zu behalten. Ein voraussichtliches Datum zur Fertigstellung des neuen Shops ist im Moment noch nicht bekannt. Du wirst allerdings umgehend hier informiert, sobald dies der Fall ist.</p>
                </div>
            </div>
        </div>

        <div class="cl"></div>

        <!-- ALL SHOPS -->
        <div class="mm-box mt42 shops w100">
            <div class="almid-h posrel">
                <div class="disfl jstfycc mb42">
                    <div class="dot mr6"></div>
                    <div class="dot mr6"></div>
                    <div class="dot"></div>
                </div>

                <div class="mmb-inr">
                    <div class="hd">
                        <p style="text-align:center;">Finde unseren Shop hier!</p>
                    </div>
                    <div class="body">
                        <div class="disfl fldirrow jstfycc">
                            <a href="https://www.etsy.com/de/shop/MeinTatenReich" target="_blank">
                                <div style="line-height:52px;" class="bgf ph12 rd3 mshd-1">
                                    <p style="color:#F45800;font-size:2.2em;font-family:'Copse';">Etsy</p>
                                </div>
                            </a>
                            <a class="ml12 disbl" href="https://www.amazon.de/s?marketplaceID=A1PA6795UKMFR9&me=A33ZW374T31RMY&merchant=A33ZW374T31RMY" target="_blank">
                                <div style="line-height:52px;height:52px;width:124px;" class="posrel bgf ph12 rd3 mshd-1">
                                    <p style="background:url(https://statics.meintatenreich.de/img/logos/a.de_logo_RGB_online_weiss.jpg) center no-repeat;background-size:cover;height:100%;width:100%;"></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php } ?>

</body>

</html>