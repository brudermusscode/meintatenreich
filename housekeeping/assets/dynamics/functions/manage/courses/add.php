<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert"
];

// objectify return array
$return = (object) $return;

// clean strings from white space + just numeric
function clean($string)
{
    $string = str_replace(' ', '', $string);
    return preg_replace('/[^0-9\,]/', '', $string);
}

if (
    isset(
        $_REQUEST['name'],
        $_REQUEST['content'],
        $_REQUEST['short'],
        $_REQUEST['price'],
        $_REQUEST['active'],
        $_REQUEST['size']
    )
    && strlen($_REQUEST['name']) > 0
    && strlen($_REQUEST['content']) > 0
    && strlen($_REQUEST['short']) > 0
    && strlen($_REQUEST['price'])  > 0
    && is_numeric($_REQUEST['active'])
    && is_numeric($_REQUEST['size'])
    && $admin->isAdmin()
) {

    // variablize
    $name = htmlspecialchars($_REQUEST['name']);
    $content = htmlspecialchars($_REQUEST['content']);
    $short = htmlspecialchars($_REQUEST['short']);
    $price = clean($_REQUEST['price']);
    $ac = $_REQUEST['active'];
    $size = $_REQUEST['size'];

    // check if price is > 1 chars
    if (strlen($price) < 1) {
        exit('2');
    }

    // transform price from , to .
    $price = str_replace(',', '.', $price);
    $price = number_format($price, 2, '.', ',');

    if ($ac == '0' || $ac == '1') {

        // start mysql transaction
        $pdo->beginTransaction();

        // insert course 
        $ins = $pdo->prepare("INSERT INTO courses (name, price, size, short, active) VALUES (?,?,?,?,?)");
        $ins = $shop->tryExecute($ins, [$name, $price, $size, $short, $ac], $pdo, false);

        if ($ins->status) {

            $needid = $ins->lastInsertId;

            // insert course content
            $ins = $pdo->prepare("INSERT INTO courses_content (cid, content) VALUES (?,?)");
            $ins = $shop->tryExecute($ins, [$needid, $content], $pdo, true);

            if ($ins->status) {

                $return->status = true;
                $return->message = "Kurs [" . $needid . "] hinzugefügt";

                exit(json_encode($return));
            } else {
                exit(json_encode($return));
            }
        } else {
            exit(json_encode($return));
        }
    } else {
        $return->message = "active status should be boolean";
        exit(json_encode($return));
    }
} else {
    $return->message = "Bitte fülle alle Felder aus";
    exit(json_encode($return));
}
