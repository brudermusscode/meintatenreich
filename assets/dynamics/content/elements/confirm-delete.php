<?php

include_once '../../../../mysql/_.session.php';

if (
    isset($_REQUEST['action'], $_REQUEST['id'])
    && $_REQUEST['action'] === 'delete'
    && is_numeric($_REQUEST['id'])
    && $loggedIn
) {

    $id = $_REQUEST['id'];

?>

    <div data-structure="confirm-window" class='posabs almid w100'>
        <div class='zoom-in bgf ml36 mr36 rd3 mshd-2'>
            <div class='p42'>
                <p style='font-size:1.2em;' class='c3 tac'>Sind Sie sicher?</p>
                <div class='jstfycc disfl mt24'>
                    <input class="hidden-elem" type="hidden" name="which" value>
                    <button data-action='request-delete' data-json='[{"id":"<?php echo $id; ?>"}]' class='hellofresh hlf-green rd3 mr12'>Ja, bitte!</button>
                    <button data-action='cancel-delete' class='hellofresh hlf-white rd3' style='color:#F34236;'>Abbrechen</button>
                </div>
            </div>
        </div>
    </div>

<?php

} else {
    exit;
}

?>