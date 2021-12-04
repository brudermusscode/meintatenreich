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
    return preg_replace('/[^0-9\,]/', '', $string);
}

if (
    isset(
        $_REQUEST['pid'],
        $_REQUEST['store'],
        $_REQUEST['gallery'],
        $_REQUEST['cid'],
        $_REQUEST['price'],
        $_REQUEST['name'],
        $_REQUEST['desc'],
        $_REQUEST['available'],
        $_REQUEST['mwstr']
    )
    && strlen($_REQUEST['name']) > 0
    && strlen($_REQUEST['desc']) > 0
    && strlen($_REQUEST['price']) > 0
    && strlen($_REQUEST['gallery']) > 0
    && is_numeric($_REQUEST['cid'])
    && is_numeric($_REQUEST['available'])
    && is_numeric($_REQUEST['mwstr'])
    && is_numeric($_REQUEST['pid'])
    && $admin->isAdmin()
) {

    // convert price format
    $name = $_REQUEST['name'];
    $cid = $_REQUEST['cid'];
    $price = clean($_REQUEST['price']);
    $price = str_replace(',', '.', $price);
    $price = number_format($price, 2, '.', ',');

    $desc = $_REQUEST['desc'];
    $mwstr = $_REQUEST['mwstr'];
    $av = $_REQUEST['available'];
    $gal = $_REQUEST['gallery'];
    $pid = $_REQUEST['pid'];

    // start mysql transaction
    $pdo->beginTransaction();

    // don't commit on first query
    $commit = false;

    // check if sent category id is existent
    $getProductsCategories = $pdo->prepare("SELECT * FROM products_categories WHERE id = ?");
    $getProductsCategories->execute([$cid]);

    if ($getProductsCategories->rowCount() > 0) {

        // if mwstr is not boolean, just set it to 0
        if (strlen($mwstr) < 1 || ($mwstr !== '0' && $mwstr !== '1')) {
            $mwstr = '0';
        }

        // same we gotta do with availability of the product
        if (strlen($av) < 1 || ($av !== '0' && $av !== '1')) {
            $av = '0';
        }

        // convert image string into array
        $images = $_REQUEST['store'];
        $images = explode(',', $images);

        // set upload path for images
        $filepath = $sroot . "/" . $url["upload"];

        // check if all images are uploaded and existent
        foreach ($images as $i) {

            if (!file_exists($filepath . '/' . $i)) {

                $return->message = "error finding uploaded image";
                exit(json_encode($return));
            }
        }

        // create product name
        $artnr = 'MTR-' . $login->createString(4);

        // try insert product
        $update = $pdo->prepare("
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
        $update = $shop->tryExecute($update, [$name, $price, $mwstr, $cid, $av, $desc, $pid], $pdo, $commit);

        if ($update->status) {

            // update products images gallery and set any to non galleric
            $update = $pdo->prepare("UPDATE products_images SET isgal = '0' WHERE pid = ?");
            $update = $shop->tryExecute($update, [$pid], $pdo, $commit);

            if ($update->status) {

                // update product images to set their pid and create a relation
                // between product and product images
                $update = $pdo->prepare("UPDATE products_images SET pid = ? WHERE url = ?");

                foreach ($images as $i) {
                    $tryUpdate = $shop->tryExecute($update, [$pid, $i], $pdo, $commit);

                    if (!$tryUpdate->status) {
                        $return->message = $update->message;
                        exit(json_encode($return));
                    }
                }

                // update gallery image
                $update = $pdo->prepare("UPDATE products_images SET isgal = '1' WHERE id = ?");
                $update = $shop->tryExecute($update, [$gal], $pdo, true);

                if ($update->status) {

                    $return->status = true;
                    $return->message = "Produkt bearbeitet";
                    exit(json_encode($return));
                } else {

                    $return->message = $update->message;
                    exit(json_encode($return));
                }
            } else {

                $return->message = "product insertion error";
                exit(json_encode($return));
            }
        } else {

            $return->message = "product insertion error";
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {

    $return->message = "Alle Felder müssen ausgefüllt sein";
    exit(json_encode($return));
}
