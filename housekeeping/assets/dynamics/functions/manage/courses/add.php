<?php


    // ERROR CODE :: 0
    require_once "../../../../../../mysql/_.session.php";


    function clean($string) {
       $string = str_replace(' ', '', $string);
       return preg_replace('/[^0-9\,]/', '', $string);
    }

    if(isset(
        $_REQUEST['name'], $_REQUEST['content'], $_REQUEST['short'], $_REQUEST['price'], $_REQUEST['active'], $_REQUEST['size']
       ) 
       && strlen($_REQUEST['name']) > 0 
       && strlen($_REQUEST['content']) > 0 
       && strlen($_REQUEST['short']) > 0 
       && strlen($_REQUEST['price'])  > 0 
       && strlen($_REQUEST['size'])  > 0 
       && is_numeric($_REQUEST['active']) 
       && $loggedIn 
       && $user['admin'] === '1') {
        
        // CLEAR VARS
        $name = htmlspecialchars($_REQUEST['name']);
        $content = htmlspecialchars($_REQUEST['content']);
        $short = htmlspecialchars($_REQUEST['short']);
        $price = clean($_REQUEST['price']);
        if(strlen($price) < 1) {
            exit('2'); // Price is invalid
        }
        $price = str_replace(',', '.', $price);
        $price = number_format($price, 2, '.', ',');
        $ac = htmlspecialchars($_REQUEST['active']);
        $size = htmlspecialchars($_REQUEST['size']);
        
        if($ac === '0' || $ac === '1') {
            if(is_numeric($size)) {

                $ins = $c->prepare("
                    INSERT INTO courses (name, price, size, short, active, timestamp, updated)
                    VALUES (?,?,?,?,?,?,?)
                ");
                $ins->bind_param('sssssss', $name, $price, $size, $short, $ac, $timestamp, $timestamp);
                $ins->execute();

                $needid = $ins->insert_id;

                $insCont = $c->prepare("
                    INSERT INTO courses_content (couid, content, timestamp)
                    VALUES (?,?,?)
                ");
                $insCont->bind_param('sss', $needid, $content, $timestamp);
                $insCont->execute();

                if($ins && $insCont) {
                    $c->commit();
                    $c->close();
                    exit('success');
                } else {
                    $c->rollback();
                    $c->close();
                    exit('0');
                }


            } else {
                exit('3');
            }

        } else {
            exit('1');
        }

    } else {
        exit('ok');
    }
