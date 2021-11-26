<?php

// ERROR CODE :: 0

require_once "../../../mysql/_.session.php";

if (
    isset($_REQUEST['action'], $_REQUEST['vote'],  $_REQUEST['cid'],  $_REQUEST['pid'],  $_REQUEST['uid'])
    && $_REQUEST['action'] === 'comment-vote'
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
    $sel = $c->prepare("SELECT * FROM products_comments WHERE id = ? AND uid = ? AND pid = ?");
    $sel->bind_param('sss', $cid, $cuid, $cpid);
    $sel->execute();
    $sel_r = $sel->get_result();

    if ($sel_r->rowCount() > 0) {

        $cex = $sel_r->fetch_assoc();
        $sel->close();

        $upvotes = $cex['up'];
        $dwvotes = $cex['down'];

        // CHECK IF VOTED ALREADY
        $sel = $c->prepare("SELECT * FROM products_comments_votes WHERE uid = ? AND cid = ?");
        $sel->bind_param('ss', $cuid, $cid);
        $sel->execute();
        $sel_r = $sel->get_result();

        if ($sel_r->rowCount() > 0) {

            $s = $sel_r->fetch_assoc();
            $sel->close();

            if ($s['vote'] === $vote) {

                // BUILD QUERY FOR UPDATE COMMENT
                $votes = false;
                if ($vote === 'up') {
                    $votes = $upvotes - 1;
                    $sql = "UPDATE products_comments SET up = ?";
                } else {
                    $votes = $dwvotes - 1;
                    $sql = "UPDATE products_comments SET down = ?";
                }

                // UPDATE COMMENT
                $updCom = $c->prepare($sql);
                $updCom->bind_param('s', $votes);
                $updCom->execute();

                // UPDATE VOTE
                $upd = $c->prepare("UPDATE products_comments_votes SET vote = 'none', active = '0' WHERE uid = ? AND cid = ?");
                $upd->bind_param('ss', $cuid, $cid);
                $upd->execute();

                if ($upd && $updCom) {
                    $c->commit();
                    $updCom->close();
                    $upd->close();
                    $c->close();
                    exit('inactive');
                } else {
                    $c->rollback();
                    $updCom->close();
                    $upd->close();
                    $c->close();
                    exit('0');
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
                $updCom = $c->prepare($sql);
                $updCom->bind_param('ssss', $up, $dw, $cuid, $cpid);
                $updCom->execute();

                // UPDATE VOTE
                $upd = $c->prepare("UPDATE products_comments_votes SET vote = ?, timestamp = ?, active = '1' WHERE uid = ? AND cid = ?");
                $upd->bind_param('ssss', $vote, $timestamp, $cuid, $cid);
                $upd->execute();

                if ($upd && $updCom) {
                    $c->commit();
                    $upd->close();
                    $updCom->close();
                    $c->close();
                    exit($exit);
                } else {
                    $c->rollback();
                    $upd->close();
                    $updCom->close();
                    $c->close();
                    exit('0');
                }
            }
        } else {

            // UPDATE VOTE
            $ins = $c->prepare("INSERT INTO products_comments_votes (uid, cid, vote, timestamp, active) VALUES (?,?,?,?,'1')");
            $ins->bind_param('ssss', $cuid, $cid, $vote, $timestamp);
            $ins->execute();

            if ($ins) {
                $c->commit();
                $ins->close();
                $c->close();
                exit($exit);
            } else {
                $c->rollback();
                $ins->close();
                $c->close();
                exit('0');
            }
        }
    } else {
        exit('1');
    }
} else {
    exit;
}
