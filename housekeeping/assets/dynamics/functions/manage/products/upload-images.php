<?php


    // ERROR CODE :: 0
    require_once "../../../../../../mysql/_.session.php";
    require_once '../../../libs/bulletproof/upload.php';

    
    $image = new Bulletproof\Image($_FILES);

    if($image['pictures']) {
        
        // IMPORTANT
        $strn = $login->createString(24);
        
        // SET NEW LOCATION
        $image->setLocation('../../../../../../' . $uploaddir);
        
        // PASS TOGETHER VARS
        $imgname = $image->setName('prod-' . $strn);
        $mime = $image->getMime();  
        $fullname = trim('prod-' . $strn . '.' . $mime);  
        
        // UPLOAD IT
        $upload = $image->upload(); 

        if($upload){
            
            $res = ['status' => '1', 'url' => $fullname];
            
            exit(json_encode($res));
            
        } else {
            
            $res = ['status' => '0'];
            
            exit(json_encode($res));
            
        }
        
    } else {
        exit;
    }


?>
