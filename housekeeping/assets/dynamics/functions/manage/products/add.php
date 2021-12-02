<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


function clean($string)
{
    $string = str_replace(' ', '', $string);
    return preg_replace('/[^0-9\,]/', '', $string);
}

if (
    isset($_REQUEST['images'], $_REQUEST['gallery'], $_REQUEST['cid'], $_REQUEST['price'], $_REQUEST['name'], $_REQUEST['desc'], $_REQUEST['available'], $_REQUEST['mwstr'])
    && strlen($_REQUEST['images']) > 0
    && strlen($_REQUEST['name']) > 0
    && strlen($_REQUEST['desc']) > 0
    && strlen($_REQUEST['price']) > 0
    && is_numeric($_REQUEST['cid'])
    && is_numeric($_REQUEST['available'])
    && is_numeric($_REQUEST['mwstr'])
    && $loggedIn
    && $user['admin'] === '1'
) {

    $name = $_REQUEST['name'];
    $cid = $_REQUEST['cid'];
    $price = clean($_REQUEST['price']);

    if (strlen($price) < 1) {
        exit('3'); // Price is invalid
    }

    $price = str_replace(',', '.', $price);
    $price = number_format($price, 2, '.', ',');
    $desc = htmlspecialchars($_REQUEST['desc']);
    $mwstr = $_REQUEST['mwstr'];
    $av = $_REQUEST['available'];
    $gal = htmlspecialchars($_REQUEST['gallery']);

    if (strlen($gal) < 1) {
        exit('4');
    }

    // CHECK CATEGORY
    $selCat = $pdo->prepare("SELECT * FROM products_categories WHERE id = ?");
    $selCat->bind_param('s', $cid);
    $selCat->execute();
    $selCat_r = $selCat->get_result();

    if ($selCat_r->rowCount() > 0) {

        if (strlen($mwstr) < 1 || ($mwstr !== '0' && $mwstr !== '1')) {
            $mwstr = '0';
        }

        if (strlen($av) < 1 || ($av !== '0' && $av !== '1')) {
            $av = '0';
        }

        // MAKE IMAGE ARRAY & CHECK IMAGE EXISTENCE
        $imgarray = [];
        $images = $_REQUEST['images'];
        $pieces = explode(',', $images);
        $filepath = '../../../../../../assets/web/img/products';

        foreach ($pieces as $p) {
            if (file_exists($filepath . '/' . $p)) {
                $imgarray[] = $p;
            } else {
                exit('1');
            }
        }


        // ADD PRODUCT
        $artnr = 'MTR-' . $login->createString(4);
        $insPr = $pdo->prepare("
                INSERT INTO products (name, artnr, price, mwstr, cid, available, timestamp)
                VALUES (?,?,?,?,?,?,?)
            ");
        $insPr->bind_param('sssssss', $name, $artnr, $price, $mwstr, $cid, $av, $timestamp);
        $insPr->execute();


        // GET RELEVANT INFORMATION
        $needid = $insPr->insert_id;


        // INSERT GALLERY IMAGE AND REMOVE FROM ARRAY
        if (file_exists($filepath . '/' . $gal) && ($key = array_search($gal, $imgarray)) !== false) {

            $insPrImgGal = $pdo->prepare("
                    INSERT INTO products_images (isgal, pid, url, timestamp)
                    VALUES ('1',?,?,?)
                ");
            $insPrImgGal->bind_param('sss', $needid, $gal, $timestamp);
            $insPrImgGal->execute();

            unset($imgarray[$key]);
        } else {
            exit('0');
        }

        if (in_array($gal, $imgarray)) {
            exit('0');
        }


        // ADD PRODUCT DESCRIPTION
        $insPrDesc = $pdo->prepare("
                INSERT INTO products_desc (pid, text)
                VALUES (?,?)
            ");
        $insPrDesc->bind_param('ss', $needid, $desc);
        $insPrDesc->execute();


        // ADD IMAGES
        $insPrImg = true;
        foreach ($imgarray as $url) {
            $insPrImg = $pdo->prepare("
                    INSERT INTO products_images (pid, url, timestamp)
                    VALUES (?,?,?)
                ");
            $insPrImg->bind_param('sss', $needid, $url, $timestamp);
            $insPrImg->execute();
        }

        if ($insPr && $insPrImg && $insPrDesc && $insPrImgGal) {
            $pdo->commit();
            $pdo->close();
            exit('success');
        } else {
            $pdo->rollback();
            $pdo->close();
            exit('0 - ass fuck');
        }
    } else {
        exit('2'); // Category doesn't exist
    }
} else {
    exit;
}
