<?php

// THIS SUCKS SO HARD ASS MAN
// HOW COULD YOU
// lmao

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

header('Content-Type: application/json; charset=utf-8');

// response array
$return = [
    "status" => false,
    "message" => "Oh nein! Ein Fehler!",
    "voteCount" => 0,
    "request" => $_REQUEST
];

// objectify response array
$return = (object) $return;

// all valid votums
$validVotes = [
    "0",
    "1"
];

if (
    isset(
        $_REQUEST["vote"],
        $_REQUEST['rid'],
        $_REQUEST['pid']
    ) &&
    in_array($_REQUEST["vote"], $validVotes) &&
    is_numeric($_REQUEST['rid']) &&
    is_numeric($_REQUEST['pid']) &&
    $loggedIn
) {

    $vote = $_REQUEST["vote"];
    $rid = $_REQUEST['rid']; // rating id
    $pid = $_REQUEST['pid']; // product id

    // start mysql transaction
    $pdo->beginTransaction();

    // check up if the rating exists
    $sel = $pdo->prepare("SELECT * FROM products_ratings_comments WHERE id = ? AND pid = ?");
    $sel->execute([$rid, $pid]);

    if ($sel->rowCount() > 0) {

        // getch comment information
        $s = $sel->fetch();

        // get current vote amount
        $upvotes = $s->up;
        $downvotes = $s->down;

        // check if voted already
        $sel = $pdo->prepare("SELECT * FROM products_ratings_comments_votes WHERE rid = ? AND uid = ?");
        $sel->execute([$rid, $my->id]);

        if ($sel->rowCount() < 1) {

            if ($vote == 0) {

                $newVoteCount = $downvotes + 1;
                $sql = "UPDATE products_ratings_comments SET down = ? WHERE id = ? AND pid = ?";
            } else {

                $newVoteCount = $upvotes + 1;
                $sql = "UPDATE products_ratings_comments SET up = ? WHERE id = ? AND pid = ?";
            }

            // update comment
            $upd = $pdo->prepare($sql);
            $upd = $shop->tryExecute($upd, [$newVoteCount, $rid, $pid], $pdo, false);

            if ($upd->status) {

                // insert voting
                $ins = $pdo->prepare("INSERT INTO products_ratings_comments_votes (uid, rid) VALUES (?,?)");
                $ins = $shop->tryExecute($ins, [$my->id, $rid], $pdo, true);

                if ($upd->status) {

                    $return->status = true;
                    $return->message = "Votum abgegeben!";
                    $return->voteCount = $newVoteCount;
                    exit(json_encode($return));
                } else {
                    exit(json_encode($return));
                }
            } else {
                exit(json_encode($return));
            }
        } else {
            $return->message = "Du hast bereits für diese Bewertung gevoted";
            exit(json_encode($return));
        }
    } else {
        exit(json_encode($return));
    }
} else {
    exit(json_encode($return));
}
