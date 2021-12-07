<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!"
];

// objectify response array
$return = (object) $return;

if (
    isset($_REQUEST['action'], $_REQUEST['id'], $_REQUEST['comment'], $_REQUEST['rate'])
    && $_REQUEST['action'] === 'submit-comment'
    && is_numeric($_REQUEST['id'])
    && is_numeric($_REQUEST['rate'])
    && $_REQUEST['comment'] !== ''
    && $loggedIn
) {

    $comment = $_REQUEST['comment'];
    $id = htmlspecialchars($_REQUEST['id']);
    $uid = htmlspecialchars($my->id);
    $rate = htmlspecialchars($_REQUEST['rate']);

    // CHECK IF PRODUCT EXISTS
    $select = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $select->execute([$id]);

    if ($select->rowCount() > 0) {

        $pr = $select->fetch();

        // CHECK IF BOUGHT
        $select = $pdo->prepare("
            SELECT customer_buys.uid, customer_buys_products.pid 
            FROM customer_buys, customer_buys_products 
            WHERE customer_buys.id = customer_buys_products.bid 
            AND customer_buys.uid = ? 
            AND customer_buys_products.pid = ?
        ");
        $select->execute([$uid, $id]);

        if ($select->rowCount() > 0) {

            // validate comment
            if (preg_match('/^[a-zA-Z0-9 +\-.,"%&äöüÄÖÜß?!()_´`\^\/°]+$/', $comment)) {

                // validate star rating 1-5
                if (preg_match('/^[1-5]+$/', $rate)) {

                    // CHECK IF RATED ALREADY
                    $select = $pdo->prepare("SELECT * FROM products_ratings_comments WHERE uid = ? AND pid = ?");
                    $select->execute([$uid, $id]);

                    if ($select->rowCount() < 1) {

                        // start mysqö transaction
                        $pdo->beginTransaction();

                        // insert the comment
                        $ins = $pdo->prepare("INSERT INTO products_ratings_comments (uid,pid,text) VALUES (?,?,?)");
                        $ins = $shop->tryExecute($ins, [$uid, $id, $comment], $pdo, false);

                        if ($ins->status) {

                            $needid = $ins->lastInsertId;

                            // insert the rating
                            $ins = $pdo->prepare("INSERT INTO products_ratings (uid,cid,rate) VALUES (?,?,?)");
                            $ins = $shop->tryExecute($ins, [$uid, $needid, $rate], $pdo, false);

                            if ($ins->status) {

                                // insert admin log
                                $ins = $pdo->prepare("INSERT INTO admin_overview (rid,ttype) VALUES (?,'comment')");
                                $ins = $shop->tryExecute($ins, [$needid], $pdo, true);

                                if ($ins->status) {

                                    $return->status = true;
                                    $return->message = "Bewertung abgegeben";
                                    exit(json_encode($return));
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
                        $return->message = "Du hast dieses Produkt schon bewertet";
                        exit(json_encode($return));
                    }
                } else {
                    $return->message = "Bewertungen nur von 1 - 5";
                    exit(json_encode($return));
                }
            } else {
                $return->message = "Dein Kommentar enthält ungültige Zeichen";
                exit(json_encode($return));
            }
        } else {
            $return->message = "Du musst das Produkt kaufen, um es bewerten zu können";
            exit(json_encode($return));
        }
    } else {
        $return->message = "Das Produkt scheint nicht mehr zu existieren";
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
