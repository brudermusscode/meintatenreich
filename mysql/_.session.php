<?php

// create session when cookies are accepted and no
// session was started before
if (!isset($_SESSION) && isset($_COOKIE['cookies']) && $_COOKIE['cookies'] == 'true') {
    session_start();
}

// include prepare file
require_once "_.prepare.php";

// get system settings
$get_system_settings = $pdo->prepare("SELECT * FROM system_settings, system_urls WHERE system_urls.id = ?");
$get_system_settings->execute([$conf["environment"]]);
$system_settings = $get_system_settings->fetch();

// objectify my array
$my = (object) $_SESSION;

// create main array with system specific information
$main = [
    "name" => $system_settings->name,
    "year" => $system_settings->year,
    "maintenance" => $system_settings->maintenance,
    "displayerrors" => $system_settings->display_errors,
    "mwstr" => $system_settings->mwstr,
    "fulldate" => date("Y-m-d H:i:s"),
    "endlessCookie" => time() + (10 * 365 * 24 * 60 * 60)
];

// create url array with system urls
$url = [
    "main" => $system_settings->main,
    "maintenance" => $system_settings->maintenance,
    "intern" => $system_settings->intern,
    "dashbrd" => $system_settings->dashboard,
    "error" => $system_settings->error,
    "css" => $system_settings->css,
    "js" => $system_settings->js,
    "img" => $system_settings->img,
    "icons" => $system_settings->icons,
    "upload" => $system_settings->upload,
    "mobile" => $system_settings->mobile
];

// create mail array for predefined mail constructions
$mail = [
    "header"  => 'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=utf-8' . "\r\n" .
        'From: MeinTatenReich <noreply@meintatenreich.de>' . "\r\n",
    "subjectSignup" => 'Deine Registrierung auf MeinTatenreich - Jetzt abschließen!',
    "subjectForgotPassword" => 'Passwort vergessen?',
    "subjectOrder" => 'Bestätigung Deiner Bestellung auf MeinTatenreich'
];

require_once "objects/Login.php";
require_once "objects/Shop.php";
require_once "objects/Admin.php";

// check for maintenance
if ($main["maintenance"] == '1') {
    if ($login->isAuthed($pdo)) {
        if ($user['admin'] !== '1') {
            header('location: ./maintenance');
        }
    } else if ($isauthedAdmin === false) {
        header('location: ./maintenance');
    }
}


// get information about logged in customer
$loggedIn = $login->isAuthed($pdo);

if ($loggedIn) {

    // check customers billing preference
    $my->billingPreference = false;
    $billingPreference = $pdo->prepare("
        SELECT *, customer_billings.id AS bid, customer_billings_prefs.id AS bpid 
        FROM customer_billings_prefs, customer_billings  
        WHERE customer_billings.id = customer_billings_prefs.pid 
        AND customer_billings_prefs.uid = ? 
    ");
    $billingPreference->execute([$my->id]);

    if ($billingPreference->rowCount() > 0) {

        $my->billingPreference = true;
        $bp = $billingPreference->fetch();
    }

    // check customers address preference
    $my->addressPreference = false;
    $addressPreference = $pdo->prepare("
        SELECT *, customer_addresses.id AS aid, customer_addresses_prefs.id AS apid 
        FROM customer_addresses_prefs, customer_addresses  
        WHERE customer_addresses.id = customer_addresses_prefs.adid 
        AND customer_addresses_prefs.uid = ? 
    ");
    $addressPreference->execute([$my->id]);

    if ($addressPreference->rowCount() > 0) {

        $my->addressPreference = true;
        $ap = $addressPreference->fetch();
    }


    // check authentification for admin cookie
    $isauthedAdmin = false;
    if (isset($_COOKIE['EzGqsVq6rY8xE5'])) {
        if ($_COOKIE['EzGqsVq6rY8xE5'] === 'CjkzqEy2uhSsqc') {
            $isauthedAdmin = true;
        }
    }
}

include_once "_.functions.php";
