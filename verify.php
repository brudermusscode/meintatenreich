<?php

require_once "./mysql/_.session.php";
require_once "./mysql/_.maintenance.php";

$ptit = "Verifiziere deinen Account";
$pid = "verify";

if (isset($_GET['id'], $_GET['key'])) {
    $id = $_GET['id'];
    $key = $_GET['key'];

    // CHECK
    $sel = $c->prepare('SELECT * FROM customer WHERE id = ? AND verification_key = ?');
    $sel->bind_param('ss', $id, $key);
    $sel->execute();
    $s_r = $sel->get_result();

    if ($s_r->rowCount() > 0) {

        $s = $s_r->fetch_assoc();

        if ($s['verified'] === '1') {
            $ver = 'verified';
        } else {

            $upd = $c->prepare("UPDATE customer SET verified = '1' WHERE id = ? AND verification_key = ?");
            $upd->bind_param('ss', $id, $key);
            $upd->execute();

            if ($upd) {
                $c->commit();
                $ver = 'success';
            } else {
                $c->rollback();
                $ver = 'error';
            }

            $c->close();
            $upd->close();
        }
    } else {
        $ver = 'notexist';
    }

    $sel->close();
} else {
    exit(header('location: ./'));
}

include_once "./assets/templates/global/head.php";

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

<div class="easy-main">

    <div class="easy-hdr">
        <img onload="fadeInVisOpa($(this))" class="tran-all" src="<?php echo $imgurl; ?>/global/g3766.png">
    </div>

    <div class="easy-box mshd-1 rd3">
        <div class="p32">
            <p class="tac">
                <?php if ($ver === 'notexist') { ?>
                    Der Nutzer scheint nicht zu existieren!
                <?php } else if ($ver === 'verified') { ?>
                    Dieser Nutzer ist bereits verifiziert!
                <?php } else if ($ver === 'error') { ?>
                    Ein unbekannter Fehler ist aufgetreten. Bitte versuche es erneut!
                <?php } else { ?>
                    Du hast deinen Account erfolgreich verifiziert!
                <?php } ?>
            </p>
        </div>
    </div>

    <div class="mt24">
        <div class="rt">
            <a href="/">
                <div class="hellofresh hlf-green normal rd3 mshd-1">
                    <p>Zurück</p>
                </div>
            </a>
        </div>

        <div class="cl"></div>
    </div>

</div>


</body>

</html>