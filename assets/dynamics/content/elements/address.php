<?php

include_once '../../../../mysql/_.session.php';

if (
    isset($_REQUEST['fullname'], $_REQUEST['country'], $_REQUEST['str'], $_REQUEST['hnr'], $_REQUEST['extra'], $_REQUEST['postcode'], $_REQUEST['tel'], $_REQUEST['city'])
    && is_numeric($_REQUEST['adid'])
    && $loggedIn
) {

?>


    <div id="np-<?php echo $_REQUEST['adid']; ?>" class="notice-papel lt" data-action="edit-address" data-json='[{"uid":"<?php echo $my->id; ?>", "adid":"<?php echo $_REQUEST['adid']; ?>"}]'>

        <div class="edit-pm tran-all-cubic">
            <p class="almid"><i class="icon-edit-3"></i></p>
        </div>

        <div class="needle"></div>
        <div class="np-inr">
            <div class="option mt12">
                <p class="desc ttup  w100">NAME</p>
                <p class="actual trimfull w100"><?php echo $_REQUEST['fullname']; ?></p>
            </div>
            <div class="option mt12">
                <p class="desc ttup  w100">Anschrift</p>
                <p class="actual trimfull w100">
                    <?php
                    if ($_REQUEST['extra'] !== '' && $_REQUEST['extra'] !== 'none') {
                        echo $_REQUEST['address'] . ', ' . $_REQUEST['extra'];
                    } else {
                        echo $_REQUEST['address'];
                    }
                    ?>
                </p>
                <p class="actual trimfull w100"><?php echo $_REQUEST['postcode'] . ' ' . $_REQUEST['city']; ?></p>
            </div>
            <div class="option mt12">
                <p class="desc ttup  w100">Telefonnummer</p>
                <p class="actual trimfull w100"><?php echo $_REQUEST['tel']; ?></p>
            </div>

        </div>
    </div>




<?php

    exit;
} else {
    exit;
}

?>