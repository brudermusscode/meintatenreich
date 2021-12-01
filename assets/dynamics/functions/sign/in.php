<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

if (
    isset($_REQUEST['mail'], $_REQUEST['password'])
    && $_REQUEST['mail'] !== ''
    && $_REQUEST['password'] !== ''
    && !$loggedIn
) {

    if (isset($_COOKIE['cookies']) && $_COOKIE['cookies'] == 'true') {

        // set variables for requested values
        $mail = htmlspecialchars($_REQUEST["mail"]);
        $pass = $_REQUEST["password"];
        $commit = true;

        // get user data and compare
        $getUserData = $pdo->prepare("SELECT * FROM customer WHERE mail = ?");
        $getUserData->execute([$mail]);

        if ($getUserData->rowCount() > 0) {

            // fetch user information and create object
            $u = $getUserData->fetch();
            $loginpass = $u->password;

            if (password_verify($pass, $loginpass)) {

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
                $updateSession = $shop->tryExecute($updateSession, [$uid, $token, $serial, $remoteaddr, $httpxfor, $uid], $pdo, $commit);

                if (is_array($updateSession) && $updateSession["status"]) {

                    $getShoppingCardAmount = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ? AND active = '1'");
                    $getShoppingCardAmount->execute([$uid]);

                    $login->createCookie($token, $serial);
                    $login->createSession($u, $token, $serial, $getShoppingCardAmount->rowCount());

                    exit("success");
                } else {
                    exit("0");
                }
            } else {
                exit('4');
            } // Password
        } else {
            exit('3');
        } // Username
    } else {
        exit('2');
    } // Cookies
} else {
    exit('1');
} // unknown error
