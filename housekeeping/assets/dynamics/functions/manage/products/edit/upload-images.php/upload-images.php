<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";
require_once '../../../libs/bulletproof/upload.php';


$image = new Bulletproof\Image($_FILES);

if ($image['pictures'] && isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {

    // IMPORTANT
    $strn = $login->createString(24);
    $id = $_REQUEST['id'];

    // CHECK PRODUCT EXISTENCE
    $sel = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $sel->bind_param('s', $id);
    $sel->execute();
    $sr = $sel->get_result();
    $sel->close();

    if ($sr->rowCount() > 0) {

        // SET NEW LOCATION
        $image->setLocation('../../../../../../' . $uploaddir);

        // PASS TOGETHER VARS
        $imgname = $image->setName('prod-' . $strn);
        $mime = $image->getMime();
        $fullname = trim('prod-' . $strn . '.' . $mime);

        // UPLOAD IT
        $upload = $image->upload();

        if ($upload) {

            // INSERT IMAGE
            $ins = $pdo->prepare("INSERT INTO products_images (pid, url, timestamp) VALUES (?,?,?)");
            $ins->bind_param('sss', $id, $fullname, $timestamp);
            $ins->execute();

            // GET RELEVANT INFORMATION
            $needid = $ins->insert_id;
            $res = ['status' => '1', 'id' => $needid, 'url' => $fullname];

            if ($ins) {
                $pdo->commit();
                $pdo->close();
                exit(json_encode($res));
            } else {
                $pdo->rollback();
                $pdo->close();
                $res = ['status' => '0'];
                exit(json_encode($res));
            }
        } else {

            $res = ['status' => '0'];
            exit(json_encode($res));
        }
    } else {
        $res = ['status' => '0'];
        exit(json_encode($res));
    }
} else {
    exit;
}
