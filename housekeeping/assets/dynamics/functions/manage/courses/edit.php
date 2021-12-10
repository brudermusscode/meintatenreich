<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// set JSON output format
header('Content-Type: application/json; charset=utf-8');

// error output
$return = [
    "status" => false,
    "message" => "Da ist wohl ein Oopsie passiert",
    "REQUEST" => $_REQUEST
];

// objectify return array
$return = (object) $return;

function clean($string)
{
    $string = str_replace(' ', '', $string);
    return preg_replace('/[^0-9\,]/i', '', $string);
}

if (
    isset(
        $_REQUEST['id'],
        $_REQUEST['name'],
        $_REQUEST['content'],
        $_REQUEST['price'],
        $_REQUEST['active'],
        $_REQUEST['size']
    )
    && strlen($_REQUEST['name']) > 0
    && strlen($_REQUEST['content']) > 0
    && strlen($_REQUEST['price'])  > 0
    && is_numeric($_REQUEST['id'])
    && is_numeric($_REQUEST['active'])
    && is_numeric($_REQUEST['size'])
    && $admin->isAdmin()
) {

    // CLEAR VARS
    $id = $_REQUEST['id'];
    $name = htmlspecialchars($_REQUEST['name']);
    $content = htmlspecialchars($_REQUEST['content']);

    $price = clean($_REQUEST['price']);

    // check if price is valid format
    if (strlen($price) < 1) {
        $return->message = "Der eingegebene Preis ist ungültig";
        exit(json_encode($return));
    }

    $price = str_replace(',', '.', $price);
    $price = number_format($price, 2, '.', ',');

    $ac = htmlspecialchars($_REQUEST['active']);
    $size = htmlspecialchars($_REQUEST['size']);

    // CHECK IF COURSE EXISTS
    $sel = $pdo->prepare("
        SELECT * 
        FROM courses, courses_content  
        WHERE courses.id = courses_content.cid 
        AND courses.id = ?
    ");
    $sel->execute([$id]);

    if ($sel->rowCount() > 0) {

        // COURSE QUERY
        $s = $sel->fetch();

        if (strlen($name) < 1) {
            $name = $s->name;
        }

        if (strlen($content) < 1) {
            $content = $s->content;
        }

        if (strlen($price) < 1) {
            $price = $s->price;
        }

        if (strlen($ac) < 1 || ($ac !== '0' && $ac !== '1')) {
            $ac = $s->active;
        }

        if (strlen($size) < 1) {
            $size = $s->size;
        }

        // start mysql transaction
        $pdo->beginTransaction();

        $upd = $pdo->prepare("
            UPDATE courses INNER JOIN courses_content 
            ON (courses.id = courses_content.cid)
            SET courses.name = ?, 
                courses.price = ?, 
                courses_content.content = ?,
                courses.active = ?,
                courses.updated = CURRENT_TIMESTAMP, 
                courses.size = ?
            WHERE courses.id = ?
        ");
        $upd = $shop->tryExecute($upd, [$name, $price, $content, $ac, $size, $id], $pdo, true);

        if ($upd->status) {

            $return->status = true;
            $return->message = "Kurs [" . $id . "] bearbeitet";

            exit(json_encode($return));
        } else {
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    $return->message = "Bitte fülle alle Felder aus";
    exit(json_encode($return));
}
