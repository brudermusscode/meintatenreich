<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && $loggedIn && $user['admin'] === '1') {

    $id = $_REQUEST['id'];

    // CHECK CATEGORY EXISTENCE
    $sel = $pdo->prepare("SELECT * FROM products_categories WHERE id = ? AND id != '0'");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // UPDATE PRODUCT CATEGORY
        $upd = $pdo->prepare("UPDATE products SET cid = '0' WHERE cid = ?");
        $upd->bind_param('s', $id);
        $upd->execute();

        // DELETE
        $del = $pdo->prepare("DELETE FROM products_categories WHERE id = ?");
        $del->bind_param('s', $id);
        $del->execute();

        if ($upd && $del) {
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
