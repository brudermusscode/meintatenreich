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
    isset($_REQUEST['vote'],  $_REQUEST['cid'],  $_REQUEST['pid'],  $_REQUEST['uid'])
    && ($_REQUEST['vote'] === 'up' || $_REQUEST['vote'] === 'down')
    && is_numeric($_REQUEST['cid'])
    && is_numeric($_REQUEST['pid'])
    && is_numeric($_REQUEST['uid'])
    && $loggedIn
) {

    $vote = htmlspecialchars($_REQUEST['vote']);
    $cid = htmlspecialchars($_REQUEST['cid']);
    $cpid = htmlspecialchars($_REQUEST['pid']);
    $cuid = htmlspecialchars($_REQUEST['uid']);
    $uid = htmlspecialchars($my->id);

    if ($vote === 'up') {
        $exit = 'up';
    } else {
        $exit = 'down';
    }

    // CHECK IF COMMENT EXISTS
    $sel = $pdo->prepare("SELECT * FROM products_ratings_comments WHERE id = ? AND uid = ? AND pid = ?");
    $sel->execute([$cid, $cuid, $cpid]);

    if ($sel->rowCount() > 0) {

        $cex = $sel->fetch();

        $upvotes = $cex['up'];
        $dwvotes = $cex['down'];

        // CHECK IF VOTED ALREADY
        $sel = $pdo->prepare("SELECT * FROM products_ratings_votes WHERE uid = ? AND cid = ?");
        $sel->execute([$cuid, $cid]);

        if ($sel->rowCount() > 0) {

            $s = $sel->fetch();

            if ($s->vote === $vote) {

                // BUILD QUERY FOR UPDATE COMMENT
                $votes = false;
                if ($vote === 'up') {
                    $votes = $upvotes - 1;
                    $sql = "UPDATE products_ratings_comments SET up = ?";
                } else {
                    $votes = $dwvotes - 1;
                    $sql = "UPDATE products_ratings_comments SET down = ?";
                }

                // UPDATE COMMENT
                $updCom = $pdo->prepare($sql);
                $updCom = $shop->tryExecute($updCom, [$votes], $pdo, false);

                if ($updCom->status) {

                    // UPDATE VOTE
                    $upd = $pdo->prepare("DELETE FROM products_ratings_votes WHERE uid = ? AND cid = ?");
                    $upd = $shop->tryExecute($upd, [$cuid, $cid], $pdo, true);

                    if ($upd->status) {

                        $return->message = "Bewertung abgegeben";
                        $return->status = true;

                        exit(json_encode($result));
                    } else {
                        exit(json_encode($result));
                    }
                } else {
                    exit(json_encode($result));
                }
            } else {

                // BUILD QUERY FOR UPDATE COMMENT
                $sql = "UPDATE products_comments SET up = ?, down = ? WHERE uid = ? AND pid = ?";
                if ($s['active'] === '1') {

                    if ($vote === 'up') {
                        $up = $upvotes + 1;
                        $dw = $dwvotes - 1;
                    } else {
                        $up = $upvotes - 1;
                        $dw = $dwvotes + 1;
                    }
                } else {

                    if ($vote === 'up') {
                        $up = $upvotes + 1;
                        $dw = $dwvotes;
                    } else {
                        $up = $upvotes;
                        $dw = $dwvotes + 1;
                    }
                }


                // UPDATE COMMENT
                $updCom = $pdo->prepare($sql);
                $updCom->bind_param('ssss', $up, $dw, $cuid, $cpid);
                $updCom->execute();

                // UPDATE VOTE
                $upd = $pdo->prepare("UPDATE products_comments_votes SET vote = ?, timestamp = ?, active = '1' WHERE uid = ? AND cid = ?");
                $upd->bind_param('ssss', $vote, $timestamp, $cuid, $cid);
                $upd->execute();
            }
        } else {

            // UPDATE VOTE
            $ins = $pdo->prepare("INSERT INTO products_comments_votes (uid, cid, vote, timestamp, active) VALUES (?,?,?,?,'1')");
            $ins->bind_param('ssss', $cuid, $cid, $vote, $timestamp);
            $ins->execute();
        }
    } else {
        exit('1');
    }
} else {
    exit;
}
