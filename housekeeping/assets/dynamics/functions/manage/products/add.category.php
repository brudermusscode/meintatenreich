<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if (isset($_REQUEST['name']) && strlen($_REQUEST['name']) > 0) {

    $name = htmlspecialchars($_REQUEST['name']);

    // CHECK EXISTENCE
    $sel = $pdo->prepare("SELECT * FROM products_categories WHERE name = ?");
    $sel->bind_param('s', $name);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() < 1) {

        // INSERT CATEGORY
        $ins = $pdo->prepare("INSERT INTO products_categories (name) VALUES (?)");
        $ins->bind_param('s', $name);
        $ins->execute();

        if ($ins) {
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
