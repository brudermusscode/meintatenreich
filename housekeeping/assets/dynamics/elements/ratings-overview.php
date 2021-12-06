<?php

if (isset($elementInclude) && $admin->isAdmin()) {

    // CONVERT TIMESTAMP
    $timeAgoObject = new convertToAgo;
    $ts = $elementInclude->pcts;
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
                            if (strlen($elementInclude->firstname) > 0 && strlen($elementInclude->secondname) > 0) {
                                echo $elementInclude->firstname . ' ' . $elementInclude->secondname;
                            } else {
                                echo $elementInclude->displayname;
                            }

                            ?>
                        </p>
                    </div>

                    <div class="type lt">
                        <p>bewertung</p>
                    </div>

                    <div class="tools rt">
                        <a href="/product/<?php echo $elementInclude->artnr; ?>" target="_blank">
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

                        for ($i = 1; $i <= $elementInclude->rate; $i++) {
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
                        <p class="act rt trimfull"><?php echo $elementInclude->mail; ?></p>

                        <div class="cl"></div>
                    </div>

                    <div class="actual">
                        <p class="trimfull"><?php echo $elementInclude->text; ?></p>
                    </div>
                </div>

            </div>

            <div class="cl"></div>
        </div>

    </content-card>

<?php

} else {
    exit;
}

?>