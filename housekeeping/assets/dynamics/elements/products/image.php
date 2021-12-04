<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['id'], $_REQUEST['url']) && $admin->isAdmin()) {

    $id = $_REQUEST['id'];
    $uri = $_REQUEST['url'];

?>

    <div class="item lt" data-json='[{"id":"<?php echo $id; ?>", "url":"<?php echo $uri; ?>"}]'>
        <div class="actual-image mshd-1 tran-all-cubic">
            <img onload="fadeIn(this)" class="vishid opa0" src="<?php echo $url["img"] . '/products/' . $uri; ?>">
        </div>
    </div>

<?php

} else {
    exit(0);
}
