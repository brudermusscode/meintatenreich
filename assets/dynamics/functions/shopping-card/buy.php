<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!",
    "shoppingCardAmount" => 0,
    "price" => 0,
    "delivery" => 0
];

// objectify response array
$return = (object) $return;

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
    $getProducts = $pdo->prepare("SELECT pid FROM shopping_card WHERE uid = ?");
    $getProducts->execute([$uid]);

    if ($getProducts->rowCount() > 0) {

        if ($my->verified === '1') {

            // create product array
            $products = [];
            foreach ($getProducts->fetchAll() as $p) {
                $products[] = (int) $p->pid;
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
                            $getPrices = $pdo->prepare("SELECT price FROM products WHERE id = ?");

                            // and update all products to make them unavailable for buying
                            $update = $pdo->prepare("UPDATE products SET available = '0' WHERE id = ?");

                            foreach ($products as $pid) {
                                $getPrices->execute([$pid]);

                                if ($getPrices->rowCount() > 0) {

                                    $pr = $getPrices->fetch();
                                    $price[] = (float)$pr->price;
                                }

                                // make products unavailable
                                $tryUpdate = $shop->tryExecute($update, [$pid], $pdo, false);

                                if (!$tryUpdate->status) {

                                    $return->message = "Bei der Verarbeitung der Produkte ist ein Fehler aufgetreten";
                                    exit(json_encode($return));
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

                            // begin mysql transaction
                            $pdo->beginTransaction();

                            // insert order
                            $insertOrder = $pdo->prepare("INSERT INTO customer_buys (uid,orderid,adid,pmid,delivery,price,price_delivery) VALUES (?,?,?,?,?,?,?)");
                            $insertOrder = $shop->tryExecute($insertOrder, [$uid, $orderid, $address, $account, $delivery, $priceb, $productsDel], $pdo, false);

                            if ($insertOrder->status) {

                                // get new id of inserted order
                                $needid = $insertOrder->lastInsertId;

                                // loop through all products of shopping card
                                foreach ($products as $pid) {

                                    // and insert them into customer_buys related table
                                    $insertProducts = $pdo->prepare("INSERT INTO customer_buys_products (bid,pid) VALUES (?,?)");
                                    $insertProducts = $shop->tryExecute($insertProducts, [$needid, $pid], $pdo, false);

                                    if (!$insertProducts->status) {
                                        exit(json_encode($result));
                                    }
                                }

                                $insertAdminLog = $pdo->prepare("INSERT INTO admin_overview (rid,ttype) VALUES (?,'order')");
                                $insertAdminLog = $shop->tryExecute($insertAdminLog, [$needid], $pdo, false);

                                if ($insertAdminLog->status) {

                                    $insertBillPdf = $pdo->prepare("INSERT INTO customer_buys_pdf (pmid,adid,bid) VALUES (?,?,?)");
                                    $insertBillPdf = $shop->tryExecute($insertBillPdf, [$account, $address, $needid], $pdo, false);

                                    if ($insertBillPdf->status) {

                                        $deleteShoppingCard = $pdo->prepare("DELETE FROM shopping_card WHERE uid = ?");
                                        $deleteShoppingCard = $shop->tryExecute($deleteShoppingCard, [$uid], $pdo, true);

                                        if ($deleteShoppingCard->status) {

                                            // format price properly
                                            $price = number_format($price, 2, ',', '.');
                                            $res = [
                                                'price' => $price,
                                                'delivery' => $delivery
                                            ];

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

                                            $sendMail = $shop->trySendMail(
                                                $my->mail,
                                                "Deine Bestellung auf MeinTatenReich ist eingegangen!",
                                                $mailbody,
                                                $mailheader
                                            );

                                            if ($sendMail) {

                                                // reset shopping card amount
                                                $_SESSION["shoppingCardAmount"] = 0;

                                                $return->status = true;
                                                $return->message = "Erfolgreich, leite weiter...";
                                                $return->price = $price;
                                                $return->delivery = $delivery;

                                                exit(json_encode($return));
                                            } else {
                                                $return->status = true;
                                                $return->message = "Es konnte keine Bestätigungsmail geschickt werden";
                                                exit(json_encode($return));
                                            }
                                        } else {
                                            exit(json_encode($return));
                                        }
                                    } else {
                                        exit(json_encode($return));
                                    }
                                } else {
                                    exit(json_encode($return));
                                }
                            } else {
                                exit(json_encode($return));
                            }
                        } else {
                            exit(json_encode($return));
                        }
                    } else {
                        $return->message = "Bei der Verarbeitung deiner Adresse ist ein Fehler aufgetreten";
                        exit(json_encode($return));
                    }
                } else {
                    $return->message = "Bei der Verarbeitung deiner Zahlungsmethode ist ein Fehler aufgetreten";
                    exit(json_encode($return));
                }
            } else {
                $return->message = "Bei der Verarbeitung der Produkte ist ein Fehler aufgetreten";
                exit(json_encode($return));
            }
        } else {
            $return->message = "Bitte verifiziere dein Profil, bevor du etwas kaufen kannst";
            exit(json_encode($return));
        }
    } else {
        $return->message = "Dein Warenkorb ist leer";
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
