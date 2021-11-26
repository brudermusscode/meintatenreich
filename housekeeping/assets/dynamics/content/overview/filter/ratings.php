<?php

require_once "../../../../../../mysql/_.session.php";
require_once "../../../../../../mysql/_.maintenance.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oopsie');
    }
} else {
    header('location: /oopsie');
}

?>


<?php

$sel = $c->prepare("
        SELECT *, products_comments.timestamp AS pcts, products.artnr FROM products_comments, products_rating, customer, products 
        WHERE products_comments.id = products_rating.cid 
        AND products_comments.uid = customer.id 
        AND products_comments.pid = products.id 
        ORDER BY products_comments.timestamp 
        DESC
    ");
$sel->execute();
$sel_r = $sel->get_result();

while ($s = $sel_r->fetch_assoc()) {

    // CONVERT TIMESTAMP
    $timeAgoObject = new convertToAgo;
    $ts = $s['pcts'];
    $convertedTime = ($timeAgoObject->convert_datetime($ts));
    $when = ($timeAgoObject->makeAgo($convertedTime));

?>


    <content-card class="mb24">

        <div class="rating hd-shd">

            <!-- USER ICON -->
            <div class="user-icon">
                <div class="actual">
                    <div class="img-outer">
                        <div class="img"></div>
                    </div>
                </div>
            </div>

            <div class="rating-content rt">
                <div class="top">
                    <div class="name lt">
                        <p class="trimfull">
                            <?php

                            // CHECK CUSTOMER NAME
                            if (strlen($s['firstname']) > 0 && strlen($s['secondname']) > 0) {
                                echo $s['firstname'] . ' ' . $s['secondname'];
                            } else {
                                echo $s['displayname'];
                            }

                            ?>
                        </p>
                    </div>

                    <div class="type lt">
                        <p>bewertung</p>
                    </div>

                    <div class="tools rt">
                        <a href="/product/<?php echo $s['artnr']; ?>" target="_blank">
                            <div class="btn-outline" style="color:#FF7E8A;border-color:#FF7E8A;">
                                <p>Zum Produkt</p>
                            </div>
                        </a>
                    </div>

                    <div class="cl"></div>
                </div>

                <div class="middle">

                    <div class="stars disfl fldirrow lt">

                        <?php

                        for ($i = 1; $i <= $s['rate']; $i++) {
                            echo '<div class="one"><i class="material-icons md-18">star</i></div>';
                        }

                        ?>

                    </div>

                    <div class="timestamp">
                        <p>Bewertung ca. <?php echo $when; ?></p>
                    </div>

                    <DIV class="cl"></DIV>


                    <div class="mail">
                        <p class="icon lt">
                            <i class="material-icons md-18">mail</i>
                        </p>
                        <p class="act rt trimfull"><?php echo $s['mail']; ?></p>

                        <div class="cl"></div>
                    </div>

                    <div class="actual">
                        <p class="trimfull"><?php echo $s['text']; ?></p>
                    </div>
                </div>

            </div>

            <div class="cl"></div>
        </div>

    </content-card>



<?php } // END WHILE: ORDERS 
?>