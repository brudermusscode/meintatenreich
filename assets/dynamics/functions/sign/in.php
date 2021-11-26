<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (
    isset($_REQUEST['mail'], $_REQUEST['password'])
    && $_REQUEST['mail'] !== ''
    && $_REQUEST['password'] !== ''
    && !$loggedIn
) {

    if (isset($_COOKIE['cookies']) && $_COOKIE['cookies'] == 'true') {

        // set variables for requested values
        $mail = htmlspecialchars($_REQUEST["mail"]);
        $pass = md5($_REQUEST["password"]);

        // get user data and compare
        $getUserData = $pdo->prepare("SELECT * FROM customer WHERE mail = ? or displayname = ?");
        $getUserData->execute([$mail, $mail]);

        if ($getUserData->rowCount() > 0) {

            $user = $getUserData->fetch();
            $loginpass = $user->password;

            if ($pass == $loginpass) {

                // IMPORTANT VARIABLES
                $id = $user->id;
                $username = $user->displayname;
                $token = $login->createString(64);
                $serial = $login->createString(64);
                $remoteaddr = $_SERVER['REMOTE_ADDR'];
                $httpxfor = $login->get_client_ip();


                // check for existing session
                $getOldSession = $pdo->prepare("SELECT * FROM system_sessions WHERE uid = ?");
                $getOldSession->execute([$id]);
                $hasSession = false;
                if ($getOldSession->rowCount() > 0) {
                    $hasSession = true;
                }

                // delete old records
                if ($hasSession) {

                    $create_session = $pdo->prepare("UPDATE system_sessions SET uid = ?, token = ?, serial = ?, remoteaddr = ?, httpx = ? WHERE uid = ?");
                    $create_session->execute([$id, $token, $serial, $remoteaddr, $httpxfor, $id]);
                } else {

                    $create_session = $pdo->prepare("INSERT INTO system_sessions (uid,token,serial,remoteaddr,httpx) VALUES (?,?,?,?,?)");
                    $create_session->execute([$id, $token, $serial, $remoteaddr, $httpxfor]);
                }

                $login->createCookie($id, $username, $token, $serial);
                $login->createSession($id, $username, $token, $serial);

                if ($create_session) {

                    $pdo->commit();
                    exit('success');
                } else {

                    $pdo->rollback();
                    exit('1');
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
