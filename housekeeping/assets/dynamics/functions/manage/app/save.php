<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (
    isset($_REQUEST['maintenance'], $_REQUEST['displayerrors'])
    && $loggedIn
    && $user['admin'] === '1'
) {

    $de = $_REQUEST['displayerrors'];
    $mn = $_REQUEST['maintenance'];

    if ($de !== '1' && $de !== '0') {
        exit('0');
    }

    if ($mn !== '1' && $mn !== '0') {
        exit('0');
    }

    // UPDATE
    $upd = $pdo->prepare("UPDATE web_settings SET maintenance = ?, displayerrors = ? WHERE id = ?");
    $upd->bind_param('sss', $mn, $de, $config["sys_set_id"]);
    $upd->execute();

    if ($upd) {
        $pdo->commit();
        $pdo->close();
        exit('success');
    } else {
        $pdo->rollback();
        $pdo->close();
        exit('0');
    }
} else {
    exit;
}
