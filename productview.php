<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_GET['artnr'])) {

    // variablize
    $artnr = preg_replace("/(.+)\.php$/", "$1", $_GET['artnr']);

    // get product's information
    $getProduct = $pdo->prepare("
        SELECT *, products_categories.id AS pcatid , products.id AS prid 
        FROM products, products_desc, products_categories 
        WHERE products.id = products_desc.pid 
        AND   products.cid = products_categories.id 
        AND   products.artnr = ?
    ");
    $getProduct->execute([$artnr]);
    $p = $getProduct->fetch();

    // check if product exists
    if ($getProduct->rowCount() > 0) {

        $prid = $p->prid;
    } else {

        header('location: /');
    }

    $sNum = 0;
    $fNum = 0;
    $rNum = 0;
    if ($loggedIn) {

        // check if in shopping card
        $getShoppingCard = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ? AND pid = ?");
        $getShoppingCard->execute([$my->id, $prid]);
        $sNum = $getShoppingCard->rowCount();

        // check if is favorite
        $getFavorites = $pdo->prepare("SELECT * FROM shopping_card_remember WHERE uid = ? AND pid = ?");
        $getFavorites->execute([$my->id, $prid]);
        $fNum = $getFavorites->rowCount();

        // check for my rating
        $getRatings = $pdo->prepare("SELECT * FROM products_ratings_comments WHERE uid = ? AND pid = ?");
        $getRatings->execute([$my->id, $prid]);
        $rNum = $getRatings->rowCount();
        $r    = $getRatings->fetch();

        if ($rNum > 0) {
            $rid  = $r->id;
        }
    }
} else {

    header('location: /');
}

$ptit = $p->name;
$pid = "productview";
$rgname = 'Produktansicht';

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>


<div id="main">
    <div class="outer">
        <div class="inr">

            <div class="product-overview main-overflow-scroll">

                <div class="product-name mb12 mt12">
                    <p class="trimfull" style="font-size:1.4em;">
                        <?php echo $p->name; ?>
                        <span class="cblue" style="font-size:0.8em;">#<?php echo $p->artnr; ?></span>
                    </p>
                </div>

                <script>
                    $(function() {

                        $('.main-overflow-scroll').on("scroll", function() {

                            if ($(this).scrollTop() >= 130) {
                                $("[data-react='productview:price,toggle']").addClass('hide').css("top", "32px");
                            } else {
                                $("[data-react='productview:price,toggle']").removeClass('hide').removeAttr("style");
                            }
                        });

                    });
                </script>

                <div class="pricing-overflow" data-react="productview:price,toggle">
                    <div class="papel-klammer price rt">
                        <div class="justify">
                            <p class="ttup">Preis</p>
                            <p><?php echo number_format($p->price, 2, ',', '.'); ?> €</p>
                        </div>
                    </div>

                    <div class="cl"></div>

                    <div class="pv-buttons rt" data-json='[{"id":"<?php echo $prid; ?>"}]'>


                        <?php

                        if (!($sNum >= $p->amt)) {
                            if ($p->available == "0") { ?>

                                <div class="button black disfl fldirrow jstfycc mt12 tran-all" disabled="disabled">
                                    <p><i class="icon-shopping-basket"></i></p>
                                    <p class="ml12 trimfull">Nicht verfügbar</p>
                                </div>

                            <?php } else { ?>

                                <div class="button black disfl fldirrow jstfycc mt12 tran-all" data-action="add-scard" data-json='[{"id":"<?php echo $prid; ?>"}]'>
                                    <p><i class="icon-shopping-basket"></i></p>
                                    <p class="ml12 trimfull">In den Warenkorb</p>
                                </div>

                            <?php

                            }
                        } else {

                            ?>

                            <div class="button black disfl fldirrow jstfycc mt12 tran-all" disabled="disabled">
                                <p><i class="icon-ok"></i></p>
                                <p class="ml12 trimfull">Im Warenkorb</p>
                            </div>

                        <?php } ?>

                        <div class="button white disfl fldirrow jstfycc mt12 tran-all" data-action="add-scard-remember">
                            <?php if ($fNum < 1) { ?>
                                <p><i class="icon-star-empty"></i></p>
                                <p class="ml12 trimfull">Merken</p>
                            <?php } else { ?>
                                <p><i class="icon-star-filled"></i></p>
                                <p class="ml12 trimfull">Gemerkt</p>
                            <?php } ?>
                        </div>

                    </div>

                    <div class="cl"></div>
                </div>

                <!-- PRODUCT IMAGES -->
                <div class="po-images-outer">

                    <style>
                        .image-preview--small {
                            list-style: none;
                            padding-right: 12px;
                        }

                        .image-preview--small li {
                            height: 52px;
                            width: 52px;
                            border-radius: 12px;
                            margin-bottom: 8px;
                            border: 3px solid white;
                            cursor: pointer;
                            overflow: hidden;
                            visibility: hidden;
                            opacity: 0;
                        }

                        .image-preview--small li:hover {
                            border: 3px solid #b3e5fc;
                            opacity: .6;
                        }

                        .image-preview--small li:active {
                            transform: scale(.9);
                        }

                        .image-preview--small li.selected {
                            border: 3px solid #03a9f4;
                        }
                    </style>
                    <!-- ALL IMAGES OVERVIEW -->
                    <div class="image-preview--small lt">
                        <ul data-action="change-product-image">

                            <?php

                            // get gallery image
                            // # merge later
                            $getProductImages = $pdo->prepare("SELECT * FROM products_images WHERE pid = ? ORDER BY isgal desc LIMIT 6");
                            $getProductImages->execute([$prid]);

                            $galleryImage = $getProductImages->fetch();

                            ?>

                            <li class="selected tran-all fadeImages" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $galleryImage->url; ?>) center no-repeat;background-size:cover;" data-json='[{"url":"<?php echo $url["img"] . "/products/" . $galleryImage->url; ?>"}]'>
                                <img onload="fadeImages(this, true)" src="<?php echo $url["img"]; ?>/products/<?php echo $galleryImage->url; ?>" />
                            </li>

                            <?php

                            foreach ($getProductImages->fetchAll() as $i) {

                            ?>

                                <li class="tran-all fadeImages" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $i->url; ?>) center no-repeat;background-size:cover;" data-json='[{"url":"<?php echo $url["img"] . "/products/" . $i->url; ?>"}]'>
                                    <img onload="fadeImages(this, true)" src="<?php echo $url["img"]; ?>/products/<?php echo $galleryImage->url; ?>" />
                                </li>

                            <?php } ?>

                        </ul>
                    </div>

                    <!-- GALLERY IMAGE -->
                    <div class="gallery-image lt" data-react="productview:gallery,change" data-action="open-image-viewer" data-url="<?php echo $url["img"]; ?>/products/<?php echo $galleryImage->url; ?>">
                        <img onload="fadeImages(this)" class="almid-h" src="<?php echo $url["img"]; ?>/products/<?php echo $galleryImage->url; ?>" />
                    </div>

                    <div class="cl"></div>
                </div>

                <!-- PRODUCT OVERVIEW -->
                <div class="action-card">

                    <div class="desc">
                        <p><?php echo $p->text; ?></p>
                        <div class="tran-all more vishid opa0" data-action="open-desc" data-json='[{"id":"<?php echo $prid; ?>"}]'>
                            <p>Alles ansehen</p>
                        </div>
                    </div>

                    <div class="cl"></div>
                </div>

                <div class="cl"></div>

                <!-- product category -->
                <div class="product-categories">
                    <ul class="disfl fldirrow">
                        <li class="mshd-1"><?php echo $p->category_name; ?></li>
                    </ul>
                </div>


                <!-- COMMENT SECTION -->
                <div class="po-comments">
                    <div class="po-c-inr">

                        <p class="hd">Bewertungen</p>

                        <div class="mt24">
                            <div class="w100">


                                <!-- HEADLINE COMMENTS -->
                                <div class="c-hd">
                                    <div class="lt">

                                        <?php if ($rNum < 1 && $loggedIn) { ?>
                                            <button class="hellofresh hlf-brown mshd-1 rd3" data-action="add-rating" data-json='[{"id":"<?php echo $prid; ?>"}]'>
                                                Bewertung verfassen
                                            </button>
                                        <?php } ?>

                                    </div>
                                    <div class="rt">
                                        <div class="star-rating">
                                            <div class=""></div>
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </div>


                                <!-- MY RATING -->
                                <?php

                                if ($rNum > 0) {

                                    // get my rating
                                    $getMyRating = $pdo->prepare("SELECT * FROM products_ratings WHERE uid = ? AND cid = ? LIMIT 1");
                                    $getMyRating->execute([$my->id, $rid]);
                                    $mr = $getMyRating->fetch();

                                ?>

                                    <div class="my-rate">

                                        <div class="stars-outer disfl fldirrow jstfycc">
                                            <div class="star tran-all-cubic
                                                        <?php if ($mr->rate >= '1') {
                                                            echo ' hit';
                                                        } ?>
                                                        " data-json='[{"rate":"1"}]'>
                                                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                            </div>
                                            <div class="star tran-all-cubic
                                                        <?php if ($mr->rate >= '2') {
                                                            echo ' hit';
                                                        } ?>
                                                        " data-json='[{"rate":"2"}]'>
                                                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                            </div>
                                            <div class="star tran-all-cubic
                                                        <?php if ($mr->rate >= '3') {
                                                            echo ' hit';
                                                        } ?>
                                                        " data-json='[{"rate":"3"}]'>
                                                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                            </div>
                                            <div class="star tran-all-cubic
                                                        <?php if ($mr->rate >= '4') {
                                                            echo ' hit';
                                                        } ?>
                                                        " data-json='[{"rate":"4"}]'>
                                                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                            </div>
                                            <div class="star tran-all-cubic
                                                        <?php if ($mr->rate === '5') {
                                                            echo ' hit';
                                                        } ?>
                                                        " data-json='[{"rate":"5"}]'>
                                                <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                            </div>
                                        </div>

                                        <div class="cl"></div>

                                        <?php

                                        // get rating comments
                                        $getRatingComment = $pdo->prepare("SELECT * FROM products_ratings_comments WHERE uid = ? AND pid = ?");
                                        $getRatingComment->execute([$my->id, $prid]);

                                        foreach ($getRatingComment->fetchAll() as $rc) {

                                            // convert el timestampo
                                            $timeAgoObject = new convertToAgo;
                                            $ts = $rc->timestamp;
                                            $convertedTime = ($timeAgoObject->convert_datetime($ts));
                                            $when = ($timeAgoObject->makeAgo($convertedTime));

                                        ?>

                                            <div class="comment-outer mshd-1 rd3">
                                                <div class="user-outer disfl fldirrow">
                                                    <div class="uo-image">
                                                        <img src="<?php echo $url["img"]; ?>/elem/user.png" onload="fadeInVisOpa(this)">
                                                    </div>
                                                    <div class="uo-name">
                                                        <p class="trimfull"><?php echo $my->displayname; ?></p>
                                                    </div>
                                                    <div class="uo-date">
                                                        <p class="timeago"><?php echo $when; ?></p>
                                                    </div>
                                                </div>

                                                <div class="the-comment">
                                                    <p><?php echo $rc->text; ?></p>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>

                                <?php } else { ?>

                                    <div data-react="productview:ratings,add"></div>

                                <?php } ?>

                            </div>


                            <!-- MOST HELPFUL COMMENTS -->
                            <div class="lt w50">

                                <div class="mt24">
                                    <p><i class="icon-help-circled"></i> &nbsp; Hilfreich &nbsp; <i class="icon-down-open"></i></p>
                                </div>

                                <div class="all-comments">

                                    <?php

                                    // get helpful comments (upvoted)
                                    $getCommentsHelpful = $pdo->prepare("
                                        SELECT *, products_ratings_comments.id AS commentid 
                                        FROM products_ratings_comments, customer 
                                        WHERE products_ratings_comments.uid = customer.id 
                                        AND products_ratings_comments.pid = ? 
                                        AND products_ratings_comments.up > products_ratings_comments.down 
                                        AND products_ratings_comments.up >= 2 
                                        ORDER BY up 
                                        DESC LIMIT 6
                                    ");
                                    $getCommentsHelpful->execute([$prid]);

                                    if ($getCommentsHelpful->rowCount() < 1) {

                                    ?>

                                        <div class="bgf p24 rd3">
                                            <p class="tac">Bisher war nichts hilfreich</p>
                                        </div>

                                    <?php

                                    }

                                    foreach ($getCommentsHelpful->fetchAll() as $c) {

                                        // converto timestamperino
                                        $timeAgoObject = new convertToAgo;
                                        $pts = $c->timestamp;
                                        $convertedTime = ($timeAgoObject->convert_datetime($pts));
                                        $when = ($timeAgoObject->makeAgo($convertedTime));

                                        // get all comment's votes
                                        $getCommentsVotes = $pdo->prepare("SELECT * FROM products_comments_votes WHERE cid = ? AND vote = 'up' AND active = '1'");
                                        $getCommentsVotes->execute([$c->commentid]);
                                        $cv = $getCommentsVotes->fetch();

                                        // check my vote
                                        $getMyCommentsVotes = $pdo->prepare("SELECT * FROM products_comments_votes WHERE uid = ? AND cid = ? AND active = '1'");
                                        $getMyCommentsVotes->execute([$my->id, $c->commentid]);
                                        $mcv = $getMyCommentsVotes->fetch();

                                    ?>

                                        <div class="comment">
                                            <div class="user-outer disfl fldirrow">
                                                <div class="uo-image">
                                                    <img src="<?php echo $url["img"]; ?>/elem/user.png" onload="fadeInVisOpa(this)">
                                                </div>
                                                <div class="uo-name">
                                                    <p class="trimfull"><?php echo $c->displayname; ?></p>
                                                </div>
                                                <div class="uo-date">
                                                    <p class="timeago"><?php echo $when; ?></p>
                                                </div>
                                            </div>

                                            <div class="the-comment">
                                                <p><?php echo $c->text; ?></p>
                                            </div>

                                            <!-- Vote up/down comment -->
                                            <div class="action-outer rt" data-action="comment-vote" data-json='[{"cid":"<?php echo $c->commentid; ?>", "pid":"<?php echo $c->pid; ?>", "uid":"<?php echo $c->uid; ?>"}]'>
                                                <div class="up button disfl fldirrow
                                                            <?php

                                                            if ($getMyCommentsVotes->rowCount() > 0 && $mcv->vote === 'up') {
                                                                echo ' white';
                                                            } else {
                                                                echo ' blue';
                                                            }

                                                            ?>" data-json='[{"vote":"up"}]'>
                                                    <p><i class="icon-thumbs-up"></i></p>
                                                    <p class="ml8"><?php echo $getCommentsVotes->rowCount(); ?></p>
                                                </div>
                                                <div class="down button
                                                            <?php

                                                            if ($getMyCommentsVotes->rowCount() > 0 && $mcv->vote === 'down') {
                                                                echo ' white ';
                                                            } else {
                                                                echo ' blue ';
                                                            }

                                                            ?>" data-json='[{"vote":"down"}]'>
                                                    <p><i class="icon-thumbs-down"></i></p>
                                                </div>

                                                <div class="cl"></div>
                                            </div>

                                            <div class="cl"></div>
                                        </div>

                                    <?php } ?>

                                </div>

                            </div>


                            <!-- NEWEST COMMENTS -->
                            <div class="rt w48">
                                <div class="mt24">
                                    <p><i class="icon-sort"></i> &nbsp; Neueste &nbsp; <i class="icon-down-open"></i></p>
                                </div>

                                <div class="all-comments">

                                    <?php

                                    // get newest comments
                                    $getCommentsNew = $pdo->prepare("
                                        SELECT *, products_ratings_comments.id AS commentid 
                                        FROM products_ratings_comments, customer 
                                        WHERE products_ratings_comments.uid = customer.id 
                                        AND products_ratings_comments.pid = ? 
                                        ORDER BY products_ratings_comments.id 
                                        DESC LIMIT 6
                                    ");
                                    $getCommentsNew->execute([$prid]);

                                    if ($getCommentsNew->rowCount() < 1) {

                                    ?>

                                        <div class="bgf p24 rd3">
                                            <p class="tac">Keine Bewertungen</p>
                                        </div>

                                    <?php

                                    }

                                    foreach ($getCommentsNew->fetchAll() as $cn) {

                                        // converterino my timerstamper
                                        $timeAgoObject = new convertToAgo;
                                        $pts = $cn->timestamp;
                                        $convertedTime = ($timeAgoObject->convert_datetime($pts));
                                        $when = ($timeAgoObject->makeAgo($convertedTime));

                                        // get comment's votes
                                        $getCommentsVotes = $pdo->prepare("SELECT * FROM products_ratings_votes WHERE cid = ? AND vote = 'up'");
                                        $getCommentsVotes->execute([$cn->commentid]);
                                        $cv = $getCommentsVotes->fetch();

                                        if ($loggedIn) {
                                            // check my comment's vote
                                            $getMyCommentsVotes = $pdo->prepare("SELECT * FROM products_ratings_votes WHERE uid = ? AND cid = ?");
                                            $getMyCommentsVotes->execute([$my->id, $cn->commentid]);
                                            $mcv = $getMyCommentsVotes->fetch();
                                        }

                                    ?>

                                        <div class="comment">
                                            <div class="user-outer disfl fldirrow">
                                                <div class="uo-image">
                                                    <img src="<?php echo $url["img"]; ?>/elem/user.png" onload="fadeInVisOpa(this)">
                                                </div>
                                                <div class="uo-name">
                                                    <p class="trimfull"><?php echo $cn->displayname; ?></p>
                                                </div>
                                                <div class="uo-date">
                                                    <p class="timeago"><?php echo $when; ?></p>
                                                </div>
                                            </div>

                                            <div class="the-comment">
                                                <p><?php echo $cn->text; ?></p>
                                            </div>

                                            <!-- Vote up/down comment -->
                                            <?php if ($loggedIn) { ?>
                                                <div class="action-outer rt" data-action="comment-vote" data-json='[{"cid":"<?php echo $cn->commentid; ?>", "pid":"<?php echo $s['pid']; ?>", "uid":"<?php echo $s['uid']; ?>"}]'>
                                                    <div class="up button disfl fldirrow
                                                            <?php

                                                            if ($getMyCommentsVotes->rowCount() > 0 && $mcv->vote === 'up') {
                                                                echo ' white';
                                                            } else {
                                                                echo ' blue';
                                                            }

                                                            ?>" data-json='[{"vote":"up"}]'>
                                                        <p><i class="icon-thumbs-up"></i></p>
                                                        <p class="ml8"><?php echo $getCommentsVotes->rowCount(); ?></p>
                                                    </div>
                                                    <div class="down button
                                                            <?php

                                                            if ($getCommentsVotes->rowCount() > 0 && $mcv->vote === 'down') {
                                                                echo ' white ';
                                                            } else {
                                                                echo ' blue ';
                                                            }

                                                            ?>" data-json='[{"vote":"down"}]'>
                                                        <p><i class="icon-thumbs-down"></i></p>
                                                    </div>

                                                    <div class="cl"></div>
                                                </div>

                                            <?php } ?>

                                            <div class="cl"></div>
                                        </div>

                                    <?php } ?>

                                </div>
                            </div>

                            <div class="cl"></div>
                        </div>
                    </div>
                </div>


                <!-- SIMILIAR PRODUCTS -->
                <div class="similiar-products">
                    <div class="sp-inr">
                        <p class="sp-hd mb24">Ähnliche Produkte</p>

                        <div class="sp-products">

                            <?php

                            $getSimiliarProducts = $pdo->prepare("
                                SELECT * FROM products, products_images
                                WHERE products.cid = ?
                                AND products_images.pid = products.id
                                AND products_images.isgal = '1' 
                                AND products.id != ?
                                ORDER BY RAND()
                                LIMIT 4
                            ");
                            $getSimiliarProducts->execute([$p->pcatid, $prid]);

                            if ($getSimiliarProducts->rowCount() < 1) {

                            ?>

                                <div class="sp-none">
                                    <p><img class="tran-all" onload="fadeInVisOpa(this)" src="https://statics.meintatenreich.de/img/svg/eyes3.svg"></p>
                                    <p>Keine Produkte verfügbar</p>
                                </div>

                            <?php

                            }

                            foreach ($getSimiliarProducts->fetchAll() as $sp) {

                            ?>


                                <a href="/product/<?php echo $sp->artnr; ?>" class="tran-all">
                                    <product-card class="mshd-1">
                                        <div class="pr-inr">
                                            <div class="pr-img-outer">
                                                <div class="img" style="background:url(<?php echo $url["img"]; ?>/products/<?php echo $sp->url; ?>) center no-repeat;background-size:cover;">
                                                    <img class="tran-all" onload="fadeInVisOpa(this)" src="<?php echo $url["img"]; ?>/products/<?php echo $sp->url; ?>">
                                                </div>
                                            </div>

                                            <div class="pr-info">
                                                <p class="pr-name trimfull">
                                                    <?php echo $sp->name; ?>
                                                </p>
                                                <p class="pr-price">
                                                    <?php echo number_format($sp->price, 2, ',', '.') . ' €'; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </product-card>
                                </a>

                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(function() {

        var descBoxph = $('.product-overview').find('.desc p').height();
        var descBoxh = $('.product-overview').find('.desc').height();
        var descBoxMore = $('.product-overview').find('.desc .more');

        if (descBoxph > descBoxh) {
            descBoxMore.removeClass('opa0 vishid');
        } else {
            descBoxMore.remove();
        }

    });
</script>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>