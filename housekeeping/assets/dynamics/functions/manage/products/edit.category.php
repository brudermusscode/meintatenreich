<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (
    isset($_REQUEST['id'], $_REQUEST['name'])
    && is_numeric($_REQUEST['id'])
    && strlen($_REQUEST['name']) > 0
    && $loggedIn
    && $user['admin'] === '1'
) {

    $id = $_REQUEST['id'];
    $na = htmlspecialchars($_REQUEST['name']);

    // CHECK CATEGORY EXISTENCE
    $sel = $c->prepare("SELECT * FROM products_categories WHERE id = ? AND id != '0'");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // UPDATE PRODUCT CATEGORY
        $upd = $c->prepare("UPDATE products_categories SET name = ? WHERE id = ?");
        $upd->bind_param('ss', $na, $id);
        $upd->execute();

        if ($upd) {
            $c->commit();
            $c->close();
            exit('success');
        } else {
            $c->rollback();
            $c->close();
            exit('0');
        }
    } else {
        exit('1');
    }
} else {
    exit;
}
