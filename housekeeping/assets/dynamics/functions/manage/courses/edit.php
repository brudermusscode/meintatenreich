<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


function clean($string)
{
    $string = str_replace(' ', '', $string);
    return preg_replace('/[^0-9\,]/', '', $string);
}

if (
    isset($_REQUEST['id'], $_REQUEST['name'], $_REQUEST['content'], $_REQUEST['price'], $_REQUEST['active'], $_REQUEST['size'])
    && is_numeric($_REQUEST['id'])
    && strlen($_REQUEST['name']) > 0
    && strlen($_REQUEST['content']) > 0
    && strlen($_REQUEST['price'])  > 0
    && is_numeric($_REQUEST['active'])
    && is_numeric($_REQUEST['size'])
    && $loggedIn
    && $user['admin'] === '1'
) {

    // CLEAR VARS
    $id = $_REQUEST['id'];
    $name = htmlspecialchars($_REQUEST['name']);
    $content = htmlspecialchars($_REQUEST['content']);

    $price = clean($_REQUEST['price']);
    if (strlen($price) < 1) {
        exit('2'); // Price is invalid
    }

    $price = str_replace(',', '.', $price);
    $price = number_format($price, 2, '.', ',');

    $ac = htmlspecialchars($_REQUEST['active']);
    $size = htmlspecialchars($_REQUEST['size']);

    // CHECK IF COURSE EXISTS
    $sel = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // COURSE QUERY
        $s = $sr->fetch();

        // GET COURSE CONTENT
        $selD = $pdo->prepare("SELECT * FROM courses_content WHERE couid = ?");
        $selD->bind_param('s', $id);
        $selD->execute();
        $selD_r = $selD->get_result();
        $selD->close();

        // CONTENT QUERY
        $sc = $selD_r->fetch();

        $curcont = $sdesc['text'];

        if (strlen($name) < 1) {
            $name = $s['name'];
        }

        if (strlen($content) < 1) {
            $content = $sc['content'];
        }

        if (strlen($price) < 1) {
            $price = $s['price'];
        }

        if (strlen($ac) < 1 || ($ac !== '0' && $ac !== '1')) {
            $ac = $s['active'];
        }

        if (strlen($size) < 1) {
            $size = $s['size'];
        }

        $upd = $pdo->prepare("
                UPDATE courses INNER JOIN courses_content 
                ON (courses.id = courses_content.couid)
                SET courses.name = ?, 
                    courses.price = ?, 
                    courses_content.content = ?,
                    courses.active = ?,
                    courses.updated = ?, 
                    courses.size = ?
                WHERE courses.id = ?
            ");
        $upd->bind_param('sssssss', $name, $price, $content, $ac, $timestamp, $size, $id);
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
        exit('1');
    }
} else {
    exit;
}
