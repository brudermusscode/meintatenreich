<?php

if (isset($_REQUEST['url']) && $_REQUEST['url'] !== '') {

    $url = $_REQUEST['url'];

?>

    <style>
        image-viewer {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        image-viewer img {
            display: block;
            position: relative;
            max-height: calc(100% - 68px);
            max-width: calc(100% - 68px);
            width: auto;
            border: 6px solid rgba(255, 255, 255, .24);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

    <image-viewer>
        <img class="tran-all" src="<?php echo $url; ?>">
    </image-viewer>

<?php

} else {
    exit;
}

?>