<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';


if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'logout' && $loggedIn) {

    unset($_SESSION);
    $login->logout();
    session_destroy();

    exit('0');
} else {

    exit('1');
}
