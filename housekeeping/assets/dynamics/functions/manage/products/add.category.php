<?php


    // ERROR CODE :: 0
    require_once "../../../../../../mysql/_.session.php";

    
    if(isset($_REQUEST['name']) && strlen($_REQUEST['name']) > 0) {
        
        $name = htmlspecialchars($_REQUEST['name']);
        
        // CHECK EXISTENCE
        $sel = $c->prepare("SELECT * FROM products_categories WHERE name = ?");
        $sel->bind_param('s', $name);
        $sel->execute();
        $sr = $sel->get_result();
        $sel->close();

        if($sr->rowCount() < 1) {

            // INSERT CATEGORY
            $ins = $c->prepare("INSERT INTO products_categories (name) VALUES (?)");
            $ins->bind_param('s', $name);
            $ins->execute();
            
            if($ins) {
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
