<?php

if (isset($_REQUEST['action'], $_REQUEST['url']) && $_REQUEST['action'] === 'open-image-viewer' && $_REQUEST['url'] !== '') {

    $url = $_REQUEST['url'];

?>

    <style>
        image-viewer {
            display: block;
            max-height: calc(100vh - 24px);
            max-width: (100vw - 24px);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
        }

        image-viewer img {
            height: 400px;
            vertical-align: middle;
            position: relative;
            border: 6px solid rgba(255, 255, 255, .24);
        }
    </style>

    <image-viewer>
        <img class="tran-all zoom-in" src="<?php echo $url; ?>">
    </image-viewer>

<?php

} else {
    exit;
}

?>