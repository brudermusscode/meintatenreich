<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Ändere dein Passwort";
$pid = "verify.password";

$auth = false;

if (isset($_GET['id'], $_GET['value'])) {

    $id = $_GET['id'];
    $key = $_GET['value'];

    // get password verification key
    $getPasswordForgot = $pdo->prepare('SELECT * FROM customer_password_forgot WHERE uid = ? AND value = ?');
    $getPasswordForgot->execute([$id, $key]);

    if ($getPasswordForgot->rowCount() > 0) {

        $auth = true;
    } else {
        exit(header('location: /'));
    }
} else {
    exit(header('location: /'));
}

include_once $sroot . "/assets/templates/global/head.php";

?>

<style>
    body {
        overflow: auto;
    }

    #app {
        width: 100%;
    }

    .easy-main {
        width: 580px;
        margin: 0 auto;
        margin-bottom: 120px;
    }

    .easy-hdr {
        margin: 82px 0 42px;
        height: 120px;
        position: relative;
    }

    .easy-hdr img {
        height: 100%;
        display: block;
        margin: 0 auto;
    }

    .easy-box {
        background: white;
        word-wrap: break-word;
    }

    .main-content {
        width: 600px;
        margin: 0 auto;
        position: relative;
        font-size: 1.2em;
        border-radius: 12px;
    }

    .main-content.box {
        background-color: white;
        border-radius: 3px;
        padding: 24px 32px;
    }

    .btn {
        border: 0;
        border-radius: 3px;
        color: white;
        background: #8BC34A;
        padding: 18px 32px;
        font-size: 1.2em;
        text-align: center;
    }


    input[type="password"] {
        padding-right: 32px;
        width: calc(100% - 32px);
        outline: none;
        border: 0;
        border-bottom: 1px solid rgba(0, 0, 0, .24);
        font-size: 1em;
        font-weight: 400;
        line-height: 32px;
    }

    input[type="password"]:hover {
        border-bottom: 1px solid #C99759;
    }

    input[type="password"]:focus {
        border-bottom: 1px solid #C99759;
    }

    @media screen and (max-width:608px) {
        .easy-main {
            width: calc(100% - 24px);
        }
    }

    @media screen and (max-width:420px) {
        .easy-hdr {
            height: 80px;
        }
    }
</style>

<script>
    $(function() {

        var body = $('body');
        var focus = $('[data-react="focus"]').focus();

    });
</script>

<div class="easy-main">

    <div class="easy-hdr">
        <img onload="fadeInVisOpa($(this))" class="tran-all" src="<?php echo $url["img"]; ?>/global/g3766.png">
    </div>

    <div class="easy-box mshd-1 rd3">
        <div class="p42">

            <div class="mb24">
                <p class="tac">
                    <strong>Ändere dein Passwort</strong>
                </p>
            </div>

            <?php if ($auth === true) { ?>

                <form data-form="new-password">

                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="value" value="<?php echo $key; ?>">

                    <p>Neues Passwort</p>
                    <div class="posrel mt12">
                        <input type="password" data-react="focus" name="password" placeholder="" class="tran-all">
                        <div class="posabs" style="right:0;top:0;line-height:32px;width:32px;height:32px;text-align:center;">
                            <i class="icon-key-1"></i>
                        </div>
                    </div>

                    <p class="mt32">Neues Passwort wiederholen</p>
                    <div class="posrel mt12">
                        <input type="password" name="password2" placeholder="" class="tran-all">
                    </div>

                </form>

            <?php } ?>

        </div>
    </div>

    <div class="mt24">
        <div class="rt">
            <div data-action="request-new-password" class="hellofresh hlf-green normal rd3 mshd-1">
                <p>Speichern</p>
            </div>
        </div>

        <div class="cl"></div>
    </div>

</div>


</body>

</html>