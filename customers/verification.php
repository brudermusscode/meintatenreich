<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// start mysql transaction
$pdo->beginTransaction();

$ptit = "Verifiziere dein Profil";
$pid = "verify";

if (isset($_GET['id'], $_GET['key'])) {

    $id = $_GET['id'];
    $key = $_GET['key'];
    $status = null;

    // check for account existence
    $getCustomer = $pdo->prepare("
        SELECT *, customer.id AS uid, customer_verifications.id AS uvid 
        FROM customer, customer_verifications
        WHERE customer.id = customer_verifications.uid 
        AND customer.id = ? 
        AND customer_verifications.vkey = ? 
        AND customer_verifications.used = '0'
    ");
    $getCustomer->execute([$id, $key]);

    if ($getCustomer->rowCount() > 0) {

        // fetch users information
        $c = $getCustomer->fetch();

        if ($c->verified == '1') {

            $status = 'verified'; // already verified
        } else {

            $update = $pdo->prepare("
                UPDATE customer, customer_verifications 
                SET customer.verified = '1', customer_verifications.used = '1' 
                WHERE customer.id = customer_verifications.uid 
                AND customer.id = ? 
                AND customer_verifications.vkey = ?
            ");
            $update->execute([$id, $key]);

            if ($update) {

                $pdo->commit();
                $status = 'success'; // verified
            } else {

                $pdo->rollback();
                $status = 'error'; // some shitty error
            }
        }
    } else {

        $status = 'notexist'; // user does not exist
    }
} else {
    //exit(header('location: /oops'));
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
        <img onload="fadeInVisOpa($(this))" class="tran-all" src="<?php echo $url["img"]; ?>/global/g3766.png">
    </div>

    <div class="easy-box mshd-1 rd3">
        <div class="p42">
            <p class="tac">
                <?php if ($status === 'notexist') { ?>
                    Da stimmt was nicht!<br>Entweder existiert dieses Profil nicht, oder der Schlüssel zur Verifikation wurde bereits verwendet. Vielleicht versuchst du's einfach nochmal!
                <?php } else if ($status === 'verified') { ?>
                    Dieser Nutzer ist bereits verifiziert!
                <?php } else if ($status === 'error') { ?>
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