<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/mysql/_.session.php';

$pdo->beginTransaction();

if (isset($_REQUEST['displayname'], $_REQUEST['firstname'], $_REQUEST['secondname'])) {

    // variablize
    $displayname = htmlspecialchars($_REQUEST['displayname']);
    $firstname = htmlspecialchars($_REQUEST['firstname']);
    $lastname = htmlspecialchars($_REQUEST['secondname']);
    $uid   = $my->id;

    // create random customer name if empty
    if ($displayname === '') {

        if ($my->displayname === '') {

            $displayname = 'customer-' . $login->createString(12);
        } else {

            $displayname = $my->displayname;
        }
    } else {

        // check for valid username
        if (preg_match('/[^a-z0-9\s]/i', $displayname)) {

            exit('1'); // invalid username
        } else {

            // check for name existence
            $getCustomer = $pdo->prepare("SELECT * FROM customer WHERE displayname = ?");
            $getCustomer->execute([$displayname]);
            $c = $getCustomer->fetch();

            if ($getCustomer->rowCount() > 0 && $c->id != $uid) {

                exit('2'); // names used
            }
        }
    }

    // check firstname empty
    if ($firstname === '') {

        // set firstname to existing firstname when existing lmao
        if ($my->firstname !== '') {

            $firstname = $my->firstname;
        }
    } else {

        // validate firstname
        if (preg_match('/[^a-z\s]/i', $firstname)) {

            exit('3');
        }
    }

    // set lastname to extsining lastname when existing lmaoooo
    if ($lastname === '') {
        if ($my->secondname !== '') {
            $lastname = $my->secondname;
        }
    } else {

        // validate lastname
        if (preg_match('/[^a-z\s]/i', $lastname)) {

            exit('4');
        }
    }

    // UPDATE CUSTOMER
    $update = $pdo->prepare("UPDATE customer SET displayname = ?, firstname = ?, secondname = ? WHERE id = ?");
    $update->execute([$displayname, $firstname, $lastname, $uid]);

    if ($update) {

        $pdo->commit();
        exit('success');
    } else {

        $pdo->rollback();
        exit('0');
    }
} else {

    exit("0");
}
