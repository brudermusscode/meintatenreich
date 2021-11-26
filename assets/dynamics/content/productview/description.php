<?php

require_once "../../../../mysql/_.session.php";

if (isset($_REQUEST['action'], $_REQUEST['id']) && $_REQUEST['action'] === 'open-desc' && is_numeric($_REQUEST['id'])) {

    $id = htmlspecialchars($_REQUEST['id']);

    $getProductDescription = $pdo->prepare("
        SELECT * FROM products, products_desc 
        WHERE products.id = products_desc.pid
        AND products_desc.pid = ? LIMIT 1
    ");
    $getProductDescription->execute([$id]);
    $d = $getProductDescription->fetch();

?>


    <wide-container class="almid posabs">
        <div class="mshd-2 rd5 zoom-in bgf">

            <div class="close tran-all" data-action="close-overlay">
                <p><i class="icon-cancel-5"></i></p>
            </div>

            <div class="product-desc">
                <div class="hdr">
                    <p class="trimfull"><?php echo $d->name; ?></p>
                </div>
                <div class="text">
                    <p><?php echo $d->text; ?></p>
                </div>
            </div>

        </div>
    </wide-container>

<?php


} else {
    exit;
}

?>