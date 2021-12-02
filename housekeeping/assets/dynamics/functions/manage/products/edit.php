<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


function clean($string)
{
    $string = str_replace(' ', '', $string);
    return preg_replace('/[^0-9\,]/', '', $string);
}

if (
    isset($_REQUEST['gallery'], $_REQUEST['id'], $_REQUEST['cid'], $_REQUEST['price'], $_REQUEST['desc'], $_REQUEST['name'], $_REQUEST['mwstr'], $_REQUEST['available'])
    && is_numeric($_REQUEST['id'])
    && is_numeric($_REQUEST['cid'])
    && is_numeric($_REQUEST['gallery'])
    && is_numeric($_REQUEST['mwstr'])
    && is_numeric($_REQUEST['available'])
    && $loggedIn
    && $user['admin'] === '1'
) {

    // CLEAR VARS
    $id = $_REQUEST['id'];
    $cid = $_REQUEST['cid'];
    $gal = $_REQUEST['gallery'];
    $price = clean($_REQUEST['price']);
    $price = str_replace(',', '.', $price);
    $price = number_format($price, 2, '.', ',');
    $desc = htmlspecialchars($_REQUEST['desc']);
    $name = htmlspecialchars($_REQUEST['name']);
    $mwstr = $_REQUEST['mwstr'];
    $av = $_REQUEST['available'];

    // CHECK IF CATEGORY EXISTS
    $sel = $pdo->prepare("SELECT * FROM products_categories WHERE id = ?");
    $sel->bind_param('s', $cid);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() < 1) {
        exit('2');
    }

    // SELECT PRODUCT
    $sel = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();

    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // GET CURRENT INFORMATION
        $s = $sr->fetch();


        // CHECK GALLERY IMAGE
        $selGal = $pdo->prepare("SELECT * FROM products_images WHERE id = ? AND isgal = '1' AND pid = ?");
        $selGal->bind_param('ss', $id, $gal);
        $selGal->execute();
        $selGal_r = $selGal->get_result();
        $selGal->close();

        $updPrdImg = true;
        $updPrdImgNew = true;
        if ($selGal->rowCount() < 1) {

            $imgarray = [];
            $selGalNew = $pdo->prepare("SELECT * FROM products_images WHERE pid = ?");
            $selGalNew->bind_param('s', $id);
            $selGalNew->execute();
            $selGalNew_r = $selGalNew->get_result();

            foreach ($g = $selGalNew_r->fetchAll() as ) {
                $imgarray[] = $g['id'];
            }
            $selGalNew->close();

            foreach ($imgarray as $i) {
                $updPrdImg = $pdo->prepare("UPDATE products_images SET isgal = '0' WHERE id = ?");
                $updPrdImg->bind_param('s', $i);
                $updPrdImg->execute();
            }

            if ($updPrdImg) {
                $updPrdImgNew = $pdo->prepare("UPDATE products_images SET isgal = '1' WHERE id = ? AND pid = ?");
                $updPrdImgNew->bind_param('ss', $gal, $id);
                $updPrdImgNew->execute();
            }
        }

        // GET PRODUCT DESCRIPTION
        $selD = $pdo->prepare("SELECT * FROM products_desc WHERE pid = ?");
        $selD->bind_param('s', $id);
        $selD->execute();
        $selD_r = $selD->get_result();
        $selD->close();
        $sdesc = $selD_r->fetch();

        $curdesc = $sdesc['text'];

        if (strlen($desc) < 1) {
            $desc = $curdesc;
        }

        if (strlen($cid) < 1) {
            $cid = $s['cid'];
        }

        if (strlen($name) < 1) {
            $name = $s['name'];
        }

        if (strlen($price) < 1) {
            $price = $s['price'];
        }

        if (strlen($mwstr) < 1 || ($mwstr !== '0' && $mwstr !== '1')) {
            $mwstr = $s['mwstr'];
        }

        if (strlen($av) < 1 || ($av !== '0' && $av !== '1')) {
            $av = $s['available'];
        }

        $updProd = $pdo->prepare("
                UPDATE products INNER JOIN products_desc 
                ON (products.id = products_desc.pid)
                SET products.name = ?, 
                    products.price = ?, 
                    products.mwstr = ?,
                    products.cid = ?,
                    products.available = ?,
                    products_desc.text = ?
                WHERE products.id = ?
            ");
        $updProd->bind_param('sssssss', $name, $price, $mwstr, $cid, $av, $desc, $id);
        $updProd->execute();


        $updScard = true;
        $delRes = true;
        if ($av === '0' && $s['available'] !== '0') {
            // DELETE RESERVATIONS
            $delRes = $pdo->prepare("DELETE FROM products_reserved WHERE pid = ?");
            $delRes->bind_param('s', $id);
            $delRes->execute();

            // UPDATE SCARD
            $updScard = $pdo->prepare("UPDATE scard SET active = '0' WHERE pid = ?");
            $updScard->bind_param('s', $id);
            $updScard->execute();
        }


        if ($updProd && $delRes && $updScard && $updPrdImg && $updPrdImgNew) {
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
