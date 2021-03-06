<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!"
];

// objectify response array
$return = (object) $return;

if (
    isset($_REQUEST['mail'], $_REQUEST['password'])
    && $_REQUEST['mail'] !== ''
    && $_REQUEST['password'] !== ''
) {

    if (isset($_COOKIE['cookies']) && $_COOKIE['cookies'] == 'true') {

        // set variables for requested values
        $mail = htmlspecialchars($_REQUEST["mail"]);
        $pass = $_REQUEST["password"];

        // get user data and compare
        $getUserData = $pdo->prepare("SELECT * FROM customer WHERE mail = ?");
        $getUserData->execute([$mail]);

        if ($getUserData->rowCount() > 0) {

            // fetch user information and create object
            $u = $getUserData->fetch();
            $loginpass = $u->password;

            if ($login->verifyPassword($pass, $loginpass)) {

                // begin mysql transaction
                $pdo->beginTransaction();

                // variablize
                $uid = $u->id;
                $token = $login->createString(64);
                $serial = $login->createString(64);
                $remoteaddr = $_SERVER['REMOTE_ADDR'];
                $httpxfor = $login->get_client_ip();

                // update old session
                $updateSession = $pdo->prepare("UPDATE system_sessions SET uid = ?, token = ?, serial = ?, remoteaddr = ?, httpx = ? WHERE uid = ?");
                $updateSession = $shop->tryExecute($updateSession, [$uid, $token, $serial, $remoteaddr, $httpxfor, $uid], $pdo, true);

                if ($updateSession->status) {

                    $getShoppingCardAmount = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ?");
                    $getShoppingCardAmount->execute([$uid]);

                    $login->createCookie($token, $serial);
                    $login->createSession($u, $token, $serial, $getShoppingCardAmount->rowCount());

                    $return->status = true;
                    $return->message = "Erfolgreich eingeloggt, bitte warten...";
                    exit(json_encode($return));
                } else {
                    exit(json_encode($return));
                }
            } else {
                $return->message = "Kein Profil mit dieser Kombination aus E-Mail und Password gefunden";
                exit(json_encode($return));
            }
        } else {
            $return->message = "Kein Profil mit dieser E-Mail und Password gefunden";
            exit(json_encode($return));
        }
    } else {
        $return->message = "F??r das Einloggen m??ssen wir Cookies setzen, lies <a href='intern/privacy#cookies'>hier</a> mehr dar??ber";
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
