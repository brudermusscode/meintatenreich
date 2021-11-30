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

        // get user data and compare
        $getUserData = $pdo->prepare("SELECT * FROM customer WHERE mail = ?");
        $getUserData->execute([$mail]);

        if ($getUserData->rowCount() > 0) {

            $user = $getUserData->fetch(PDO::FETCH_ASSOC);
            $loginpass = $user["password"];

            if (password_verify($pass, $loginpass)) {

                // begin mysql transaction
                $pdo->beginTransaction();

                // variablize
                $id = $user["id"];
                $token = $login->createString(64);
                $serial = $login->createString(64);
                $remoteaddr = $_SERVER['REMOTE_ADDR'];
                $httpxfor = $login->get_client_ip();

                // update old session
                $updateSession = $pdo->prepare("UPDATE system_sessions SET uid = ?, token = ?, serial = ?, remoteaddr = ?, httpx = ? WHERE uid = ?");
                $try = $shop->tryExecute($updateSession, [$id, $token, $serial, $remoteaddr, $httpxfor, $id], $pdo);

                if (is_array($try) && $try["status"]) {

                    // create validation cookie
                    $login->createCookie($token, $serial);

                    // create validation session
                    $login->createSession($user, $token, $serial);

                    // commit
                    $pdo->commit();
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
