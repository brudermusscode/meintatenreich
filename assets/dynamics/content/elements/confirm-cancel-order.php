<?php

include_once '../../../../mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'cancel-order'
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {

    $id = $_REQUEST['id'];

?>

    <wide-container class='posabs almid'>
        <div class='zoom-in bgf rd3 mshd-2'>
            <div class='p42'>
                <p style='font-size:1.2em;' class='c3 tac'>Willst du diese Bestellung stornieren?</p>
                <div class='jstfycc disfl mt24'>
                    <input class="hidden-elem" type="hidden" name="which" value>
                    <button data-action='request-cancel-order' data-json='[{"id":"<?php echo $id; ?>"}]' class='hellofresh hlf-green rd3 mr12'>Ja, bitte!</button>
                    <button data-action="close-overlay" class='hellofresh hlf-white rd3' style='color:#F34236;'>Abbrechen</button>
                </div>
            </div>
        </div>
    </wide-container>

<?php

} else {
    exit;
}

?>