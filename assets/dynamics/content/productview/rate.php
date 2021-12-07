<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['action'], $_REQUEST['id']) && $_REQUEST['action'] === 'add-rating' && is_numeric($_REQUEST['id']) && $loggedIn) {

    $id = htmlspecialchars($_REQUEST['id']);
    $uid = htmlspecialchars($my->id);

    // CHECK IF PRODUCT EXISTS
    $select = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $select->execute([$id]);

    if ($select->rowCount() > 0) {

        $pr = $select->fetch();

        // CHECK IF BOUGHT
        $select = $pdo->prepare("
            SELECT * FROM customer_buys, customer_buys_products 
            WHERE customer_buys.id = customer_buys_products.bid 
            AND customer_buys.uid = ? 
            AND customer_buys.status = 'done' 
            AND customer_buys_products.pid = ?
        ");
        $select->execute([$uid, $id]);

        if ($select->rowCount() > 0) {

?>

            <style>
                .new-comment {
                    position: relative;
                }

                .new-comment .textarea {
                    height: 100%;
                    position: relative;
                    width: 100%;
                }

                .new-comment .textarea textarea {
                    background: white;
                    border: 0;
                    outline: none;
                    min-height: 60px;
                    width: calc(100%);
                    resize: none;
                    padding: 18px 24px;
                    color: #B88B56;
                    border-radius: 3px 3px 0 0;
                    vertical-align: middle;
                }

                .new-comment .textarea button {
                    line-height: 32px;
                    width: 100%;
                    text-align: center;
                    border-radius: 0 0 3px 3px;
                    background: rgba(0, 0, 0, .24);
                    border: 0;
                    outline: none;
                    color: #999;
                    font-size: 1.4em;
                }

                .new-comment .textarea button.active {
                    background: #2195F2;
                    color: white;
                    cursor: pointer;
                }

                .new-comment .textarea button.active:hover {
                    opacity: .8;
                }

                .new-comment .textarea button.active:active {
                    opacity: .6;
                }

                .new-comment .nc-textarea-label {
                    position: relative;
                    z-index: 1;
                    margin-bottom: 8px;
                }

                .new-comment .nc-textarea-label p {
                    color: rgba(0, 0, 0, .38);
                }

                .star-rating .sr-hd {
                    position: relative;
                    z-index: 1;
                    margin-bottom: 8px;
                }

                .star-rating .sr-hd p {
                    color: rgba(0, 0, 0, .38);
                    text-align: center;
                }

                .star-rating .stars-outer {
                    margin-bottom: 32px;
                }

                .star-rating .stars-outer .star {
                    background: url(https://statics.meintatenreich.de/img/elem/star-empty.png) center no-repeat;
                    background-size: cover;
                    height: 54px;
                    width: 54px;
                    cursor: pointer;
                    opacity: 0;
                    visibility: hidden;
                }

                .star-rating .stars-outer .star img {
                    opacity: 0;
                    visibility: hidden;
                    height: 1px;
                    width: 1px;
                }

                .star-rating .stars-outer .star:hover {
                    transform: scale(1.2);
                }

                .star-rating .stars-outer .star.hit {
                    background: url(https://statics.meintatenreich.de/img/elem/star-full.png) center no-repeat;
                    background-size: cover;
                }
            </style>

            <wide-container class="almid posabs">
                <div class="mshd-2 rd5 zoom-in bgf">
                    <div class="hd mshd-1">
                        <p>Bewertung hinzufügen</p>
                    </div>

                    <div class="close tran-all" data-action="close-overlay">
                        <p><i class="icon-cancel-5"></i></p>
                    </div>

                    <div class="body">

                        <script>
                            $(function() {

                                $(document).on('click', '[data-action="star-rating"] .star', function() {

                                    var t = $(this);
                                    var rate = t.data('json')[0].rate;
                                    var input = $('[data-form="productview:rating"]').find('input[name="rate"]').val(rate);

                                    t.parent().find('.star').removeClass('hit');
                                    t.addClass('hit').prevAll('.star').addClass('hit');

                                });

                            });
                        </script>

                        <div class="star-rating">
                            <div class="sr-hd">
                                <p>Wie sehr gefällt dir das Produkt?</p>
                            </div>
                            <div class="stars-outer disfl fldrirrow jstfycc" data-action="star-rating">
                                <div class="star tran-all-cubic" data-json='[{"rate":"1"}]'>
                                    <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                </div>
                                <div class="star tran-all-cubic" data-json='[{"rate":"2"}]'>
                                    <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                </div>
                                <div class="star tran-all-cubic" data-json='[{"rate":"3"}]'>
                                    <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                </div>
                                <div class="star tran-all-cubic" data-json='[{"rate":"4"}]'>
                                    <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                </div>
                                <div class="star tran-all-cubic" data-json='[{"rate":"5"}]'>
                                    <img src="https://statics.meintatenreich.de/img/elem/star-empty.png" onload="fadeInVisOpaBg($(this).parent())">
                                </div>
                            </div>

                        </div>

                        <div class="new-comment">
                            <div class="textarea">
                                <form data-form="productview:rating" method="POST" onsubmit="return false;" action>
                                    <div style="width:calc(100% - 48px);">
                                        <textarea data-action="po-comment" placeholder="Sag uns, was dir gefallen hat!" class="mshd-1" name="comment"></textarea>
                                        <input type="hidden" name="rate" value>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="action" value="submit-comment">
                                    </div>

                                    <div>
                                        <button type="submit" class="po-c-send mshd-1 tran-all">
                                            <i class="icon-direction-outline"></i>
                                        </button>
                                    </div>
                                </form>

                                <div class="cl"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </wide-container>

<?php

        } else {
            exit(0);
        }
    } else {
        exit(0);
    }
} else {
    exit(0);
}
