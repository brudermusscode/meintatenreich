<?php

    // ERROR CODE :: 0

    require_once "../../../../mysql/_.session.php";

    if(isset($_REQUEST['action'], $_REQUEST['mail']) 
       && $_REQUEST['action'] === 'request-new-password'
       && $_REQUEST['mail'] !== '' 
      ) {
  
        $mail = htmlspecialchars($_REQUEST['mail']);
        
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            
            // CHECK USER EXISTENCE
            $sel = $c->prepare("SELECT * FROM customer WHERE mail = ?");
            $sel->bind_param("s", $mail);
            $sel->execute();
            $s_r = $sel->get_result();
            
            if($s_r->rowCount() > 0) {
                
                $uid = $s_r->fetch_assoc();
                $uid = $uid['id'];
                $sel->close();
                
                // CHECK FGP EXISTENCE
                $sel = $c->prepare("SELECT * FROM customer_password_forgot WHERE uid = ?");
                $sel->bind_param('s', $uid);
                $sel->execute();
                $s_r = $sel->get_result();
                
                $value = $login->createString(64);
                
                if($s_r->rowCount() > 0) {
                    
                    $new = $c->prepare("UPDATE customer_password_forgot SET value = ?, timestamp = ? WHERE uid = ?");
                    $new->bind_param('sss', $value, $timestamp, $uid);
                    $new->execute();
                    
                } else {
                    
                    $new = $c->prepare("INSERT INTO customer_password_forgot (uid, value, timestamp) VALUES (?,?,?)");
                    $new->bind_param('sss', $uid, $value, $timestamp);
                    $new->execute();
                    
                }
                
                // PREPARE VERIFICATION MAIL
                $mailbody = file_get_contents('../../../templates/mail/forgot-password.html');
                $mailbody = str_replace('%url%', $purl . "/newpassword?id=" . $uid . "&value=" . $value, $mailbody);
                $mailsubject = $config['mail_fgp_subject'];
                $mailheader  = $config['mail_header'];
                
                if($new && mail($mail, $mailsubject, $mailbody, $mailheader)) {
                    $c->commit();
                    exit('successa');
                } else {
                    $c->rollback();
                    exit('0');
                }
                
                $sel->close();
                $new->close();
                $c->close();
                
            } else {
                exit('successb');
            }
            
        } else {
            exit('1');
        }
        
        
    } else {
        exit;
    }
