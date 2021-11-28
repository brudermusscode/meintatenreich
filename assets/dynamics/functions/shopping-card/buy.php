<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

header('Content-type: application/json');

$cArray = new checkArray;

if (
    isset($_REQUEST['action'], $_REQUEST['account'], $_REQUEST['address'],  $_REQUEST['delivery'])
    && $_REQUEST['action'] === 'buyshit'
    && is_numeric($_REQUEST['account'])
    && is_numeric($_REQUEST['address'])
    && $_REQUEST['delivery'] !== ''
    && $loggedIn
) {

    $uid = $my->id;
    $account  = htmlspecialchars($_REQUEST['account']);
    $address  = htmlspecialchars($_REQUEST['address']);
    $delivery = htmlspecialchars($_REQUEST['delivery']);

    // get all products from shopping card
    $getProducts = $pdo->prepare("SELECT pid FROM shopping_card WHERE uid = ? AND active = '1'");
    $getProducts->execute([$uid]);

    if ($getProducts->rowCount() > 0) {

        if ($my->verified === '1') {

            // create product array
            $products = [];
            foreach ($getProducts->fetchAll() as $p) {
                $products[] = (int)$p->pid;
            }

            if ($cArray->all($products, 'is_int')) {

                // check billing method
                $getBilling = $pdo->prepare("SELECT * FROM customer_billings WHERE id = ? AND uid = ?");
                $getBilling->execute([$account, $uid]);

                if ($getBilling->rowCount() > 0) {

                    // check address existence
                    $getAddress = $pdo->prepare("SELECT * FROM customer_addresses WHERE id = ? AND uid = ?");
                    $getAddress->execute([$address, $uid]);

                    if ($getAddress->rowCount() > 0) {

                        // validate delivery method
                        $validDels = ['single', 'combi'];
                        if (in_array($delivery, $validDels)) {

                            $price = [];

                            // recalculate prices (in case someone likes to play around)
                            foreach ($products as $pid) {

                                $getPrices = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                                $getPrices->execute([$pid]);

                                if ($getPrices->rowCount() > 0) {

                                    $pr = $getPrices->fetch();
                                    $price[] = (float)$pr->price;
                                }
                            }

                            // sumup all product prices
                            $priceb = array_sum($price);
                            $price = array_sum($price);

                            $productsDel = 0;

                            // calculate delivery costs for single package any product
                            if ($delivery === 'single') {
                                $productsDel = count($products);
                                $productsDel = $productsDel * 4;
                                $price = $productsDel + $price;
                            }

                            // create new order id
                            $orderid = $login->createString(4) . '-' . $login->createString(4) . '-' . $login->createString(4);

                            // insert order
                            $insertOrder = $pdo->prepare("INSERT INTO customer_buys (uid,orderid,adid,pmid,delivery,price,price_delivery) VALUES (?,?,?,?,?,?,?)");
                            $insertOrder->execute([$uid, $orderid, $address, $account, $delivery, $priceb, $productsDel]);

                            // get id of new order
                            $needid = $pdo->lastInsertId();

                            // loop through all products of shopping card
                            foreach ($products as $pid) {

                                // and insert them into customer_buys related table
                                $insertProducts = $pdo->prepare("INSERT INTO customer_buys_products (bid,pid) VALUES (?,?)");
                                $insertProducts->execute([$needid, $pid]);

                                // update reservation for the product
                                $updateReservations = $pdo->prepare("UPDATE products_reserved SET isorder = '1' WHERE pid = ?");
                                $updateReservations->execute([$pid]);
                            }

                            // insert admin log
                            $insertAdminLog = $pdo->prepare("INSERT INTO admin_overview (rid,ttype) VALUES (?,'order')");
                            $insertAdminLog->execute([$needid]);

                            // insert new bill as pdf
                            $insertBillPdf = $pdo->prepare("INSERT INTO customer_buys_pdf (pmid,adid,bid) VALUES (?,?,?)");
                            $insertBillPdf->execute([$account, $address, $needid]);

                            // delete shopping card entries
                            $deleteShoppingCard = $pdo->prepare("DELETE FROM shopping_card WHERE uid = ?");
                            $deleteShoppingCard->execute([$uid]);

                            // format price properly
                            $price = number_format($price, 2, ',', '.');
                            $res = ['price' => $price, 'delivery' => $delivery];

                            // prepare verification mail
                            $mailsubject = $mail['subjectOrder'];

                            if ($delivery == "single") {
                                $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/order.html');
                            } else {
                                $mailbody = file_get_contents($url["main"] . '/assets/templates/mail/orderCombi.html');
                            }
                            $mailbody = str_replace('%price%', $price, $mailbody);
                            $mailbody = str_replace('%orderid%', $orderid, $mailbody);

                            $mailheader  = $mail['header'];

                            if (
                                $insertOrder && $insertProducts && $updateReservations && $insertAdminLog && $insertBillPdf && $deleteShoppingCard &&
                                mail($my->mail, $mailsubject, $mailbody, $mailheader)
                            ) {

                                $pdo->commit();
                                exit(json_encode($res));
                            } else {

                                $pdo->rollback();
                                exit('0');
                            }
                        } else {
                            exit('6'); // invalid derlivery method
                        }
                    } else {
                        exit('5'); // no valid address
                    }
                } else {
                    exit('4'); // no valid billing method
                }
            } else {
                exit('3'); // invalid products added
            }
        } else {
            exit('2'); // user's not verified
        }
    } else {
        exit('1'); // shopping card is empty
    }
} else {
    exit("0");
}
