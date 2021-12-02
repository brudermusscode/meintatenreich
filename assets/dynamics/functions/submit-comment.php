<?php

// ERROR CODE :: 0

include_once '../../../mysql/_.session.php';

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
    $select->bind_param('s', $id);
    $select->execute();
    $s_r = $select->get_result();

    if ($s_r->rowCount() > 0) {

        $pr = $s_r->fetch_assoc();
        $select->close();

        // CHECK IF BOUGHT
        $select = $pdo->prepare("
                SELECT customer_buys.uid, customer_buys_products.pid 
                FROM customer_buys, customer_buys_products 
                WHERE customer_buys.id = customer_buys_products.bid 
                AND customer_buys.uid = ? 
                AND customer_buys_products.pid = ?");
        $select->bind_param('ss', $uid, $id);
        $select->execute();
        $s_r = $select->get_result();

        if ($s_r->rowCount() > 0) {

            $select->close();

            if (preg_match('/^[a-zA-Z0-9 +\-.,"%&äöüÄÖÜß?!()_´`\^\/°]+$/', $comment)) {

                if (preg_match('/^[1-5]+$/', $rate)) {


                    // CHECK IF RATED ALREADY
                    $select = $pdo->prepare("SELECT * FROM products_comments WHERE uid = ? AND pid = ?");
                    $select->bind_param('ss', $uid, $id);
                    $select->execute();
                    $s_r = $select->get_result();

                    if ($s_r->rowCount() < 1) {

                        $select->close();

                        $ins = $pdo->prepare("INSERT INTO products_comments (uid,pid,text,timestamp) VALUES (?,?,?,?)");
                        $ins->bind_param('ssss', $uid, $id, $comment, $timestamp);
                        $ins->execute();

                        $newid = $ins->insert_id;

                        $insRating = $pdo->prepare("INSERT INTO products_rating (uid,cid,rate,timestamp) VALUES (?,?,?,?)");
                        $insRating->bind_param('ssss', $uid, $newid, $rate, $timestamp);
                        $insRating->execute();

                        // INSERT ADMIN LOG
                        $insAOv = $pdo->prepare("INSERT INTO admin_overview (rid,ttype,timestamp) VALUES (?,'comment',?)");
                        $insAOv->bind_param('ss', $newid, $timestamp);
                        $insAOv->execute();

                        if ($ins && $insRating && $insAOv) {

                            $pdo->commit();
                            $ins->close();
                            $insRating->close();
                            $pdo->close();
                            exit('6');
                        } else {

                            $pdo->rollback();
                            $ins->close();
                            $insRating->close();
                            $pdo->close();
                            exit('0');
                        }
                    } else {
                        exit('5'); // ALREADY RATED
                    }
                } else {
                    exit('4'); // NOT 1-5 RATING
                }
            } else {
                exit('3'); // COMMENT HAS INVALID CHARS
            }
        } else {
            exit('2'); // PRODUCT NOT BOUGHT
        }
    } else {
        exit('1'); // PRODUCT DOESN#T EXIST
    }
} else {
    exit;
}
