<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['id']) && $admin->isAdmin()) {

    $oid = $_REQUEST['id'];

    // CHECK IF ORDER EXISTS
    $getOrders = $pdo->prepare("
        SELECT *, customer_buys.id AS oid, customer_buys.timestamp AS ots  
        FROM customer_buys, customer  
        WHERE customer_buys.orderid = ? 
        AND customer_buys.uid = customer.id
    ");
    $getOrders->execute([$oid]);

    if ($getOrders->rowCount() > 0) {

        // FETCH ORDER
        $o = $getOrders->fetch();

        // GET ADDRESS
        $sel = $pdo->prepare("SELECT * FROM customer_addresses WHERE id = ?");
        $sel->execute([$o->adid]);
        $adr = $sel->fetch();

        // GET PAYMENT METHOD
        $sel = $pdo->prepare("SELECT * FROM customer_billings WHERE id = ?");
        $sel->execute([$o->pmid]);
        $pmm = $sel->fetch();

        // CONVERT TIMESTAMP
        $timeAgoObject = new convertToAgo;
        $ts = $o->ots;
        $convertedTime = ($timeAgoObject->convert_datetime($ts));
        $when = ($timeAgoObject->makeAgo($convertedTime));

?>

        <wide-container class="order" style="padding-top:62px;" data-json='[{"id":"<?php echo $o->oid; ?>"}]'>


            <!-- INFORMATON BOX -->
            <div class="head-text mb12">
                <p>Übersicht</p>
            </div>
            <content-card class="mb24 posrel">
                <div class="mshd-1 normal-box">
                    <div style="padding:28px 42px;">
                        <?php if ($o->delivery === 'single') { ?>
                            <p>
                                Der Käufer <strong><?php echo $o->displayname; ?></strong> hat den <strong>Einzelversand</strong> gewählt. Die Kosten für den Versand betragen insgesamt <strong>EUR <?php echo number_format($o->price_delivery, 2, ',', '.'); ?></strong> für <strong><?php echo $o->price_delivery / 4; ?> Produkt(e)</strong>.
                            </p>
                        <?php } else { ?>

                            <?php if ($o->price_delivery == '0') { ?>
                                <p class="mb12">
                                    Der Käufer <strong><?php echo $o->displayname; ?></strong> hat den <strong>Kombi-Versand</strong> gewählt. Die Kosten für den Versand müssen dem Kunden noch mitgeteilt werden.
                                </p>
                            <?php } else { ?>
                                <p class="mb12">
                                    Der Käufer <strong><?php echo $o->displayname; ?></strong> hat den <strong>Kombi-Versand</strong> gewählt. Die Kosten für den Versand wurden durch einen Administrator auf insgesamt <strong>EUR <?php echo number_format($o->price_delivery, 2, ',', '.'); ?></strong> festgesetzt und der Käufer wurde bereits informiert.
                                </p>
                            <?php } ?>

                        <?php } ?>

                        <div class="mt18 mb8">
                            <p class="fw7">Referenz-Nr./Bestell-Nr.</p>
                        </div>
                        <div style="background:rgba(167, 46, 203, 0.12);padding:12px 24px;border-radius:4px;">
                            <p class="tac"><?php echo $o->orderid; ?></p>
                        </div>

                        <?php if ($o->price_delivery == '0') { ?>
                            <div class="hidden-input tran-all-cubic mb12 mt12" data-react="mail:deliverycosts">
                                <div class="input-outer">
                                    <form data-form="mail:deliverycosts">
                                        <input type="text" autocomplete="off" name="deliverycosts" placeholder="Versandkosten (Dezimalzahlen mit Kommata trennen)">
                                    </form>
                                </div>
                            </div>
                            <div data-action="mail:deliverycosts" class="btn-outline rt mt12" style="border-color:#AC49BD;color:#AC49BD;">
                                <p>Versandkosten mitteilen</p>
                            </div>
                        <?php } ?>

                        <div class="lt timestamp lh32 mt12">
                            <p class="ic lt"><i class="material-icons md-18 lh36">watch_later</i></p>
                            <p class="lt">Bestellung <?php echo $when; ?></p>

                            <div class="cl"></div>
                        </div>

                        <div class="cl"></div>
                    </div>
                </div>
            </content-card>


            <!-- TOOLS -->
            <div class="tools">

                <!-- T: Status -->
                <div class="statuses lt">

                    <div data-element="admin-select" data-action="manage:order,changestatus" data-list-size="262" class="delivery mshd-1 tran-all-cubic 
                    <?php

                    // CHECK STATUS
                    switch ($o->status) {
                        default:
                        case "got":
                            echo 'got';
                            break;

                        case "sent":
                            echo 'sent';
                            break;

                        case "done":
                            echo 'done';
                            break;

                        case "canceled":
                            echo 'canceled';
                            break;
                    }

                    ?>
                    ">
                        <div class="inr disfl fldirrow">
                            <p class="ic mr12"><i class="material-icons md-24"></i></p>
                            <p class="te"></p>

                            <p class="dropdown-ic lt ml18 pt6"><i class="material-icons md-24">expand_more</i></p>

                            <div class="cl"></div>
                        </div>

                        <datalist class="tran-all-cubic left">
                            <ul>
                                <li class="wic" data-json='[{"status":"canceled"}]'>
                                    <p class="ic lt" style="color:#EA363A;"><i class="material-icons md-18">clear</i></p>
                                    <p class="lt" style="color:#EA363A;">Storniert</p>

                                    <div class="cl"></div>
                                </li>
                                <li class="wic" data-json='[{"status":"got"}]'>
                                    <p class="ic lt" style="color:#FFB23B;"><i class="material-icons md-18">new_releases</i></p>
                                    <p class="lt" style="color:#FFB23B;">Neu</p>

                                    <div class="cl"></div>
                                </li>
                                <li class="wic" data-json='[{"status":"sent"}]'>
                                    <p class="ic lt" style="color:#1178CC;"><i class="material-icons md-18">flight_takeoff</i></p>
                                    <p class="lt" style="color:#1178CC;">Versandt</p>

                                    <div class="cl"></div>
                                </li>
                                <li class="wic" data-json='[{"status":"done"}]'>
                                    <p class="ic lt" style="color:#3EAF5C;"><i class="material-icons md-18">task_alt</i></p>
                                    <p class="lt" style="color:#3EAF5C;">Abgeschlossen</p>

                                    <div class="cl"></div>
                                </li>
                            </ul>
                        </datalist>
                    </div>

                    <!-- T: Paid -->
                    <?php if ($o->status !== 'canceled') { ?>
                        <div data-element="admin-select" data-action="manage:order,paid" data-list-size="262" class="paid mshd-1 <?php if ($o->paid == '2') {
                                                                                                                                        echo 'ok';
                                                                                                                                    } ?>">
                            <div class="inr">
                                <?php

                                if ($o->paid == '0') {
                                    echo '
                                    <p class="ic lt mr12"><i class="material-icons md-24">watch_later</i></p>
                                    <p class="te lt">Nicht bezahlt</p>
                                ';
                                } else if ($o->paid == '1') {
                                    echo '
                                    <p class="ic lt mr12"><i class="material-icons md-24">indeterminate_check_box</i></p>
                                    <p class="te lt">Als bezahlt markiert</p>
                                ';
                                } else {
                                    echo '
                                    <p class="ic lt mr12"><i class="material-icons md-24">check_box</i></p>
                                    <p class="te lt">Bezahlt</p>
                                ';
                                }
                                ?>


                                <p class="dropdown-ic lt ml18 pt6"><i class="material-icons md-24">expand_more</i></p>

                                <div class="cl"></div>
                            </div>

                            <datalist class="tran-all-cubic left">
                                <ul>
                                    <li class="wic" data-json='[{"status":"2"}]'>
                                        <p class="ic lt"><i class="material-icons md-18">check_box</i></p>
                                        <p class="lt">Bezahlt</p>

                                        <div class="cl"></div>
                                    </li>
                                </ul>
                            </datalist>
                        </div>
                    <?php } ?>

                </div>

                <!-- T: Pricing -->
                <div class="pricing rt mshd-1">
                    <p class="del-m">
                        <?php if ($o->delivery === 'single') {
                            echo 'Einzelversand';
                        } else {
                            echo 'Kombi-Versand';
                        }; ?>
                    </p>

                    <?php if ($o->price_delivery != '0') { ?>
                        <p>
                            <?php echo '<strong>' . number_format($o->price, 2, ',', '.') . '</strong> EUR'; ?>
                        </p>
                        <p>
                            <?php echo '+ <strong>' . number_format($o->price_delivery, 2, ',', '.') . '</strong> EUR'; ?>
                        </p>
                    <?php } ?>

                    <p>
                        <?php echo '= <strong>' . number_format($o->price + $o->price_delivery, 2, ',', '.') . '</strong> EUR'; ?>
                    </p>
                </div>

                <div class="cl"></div>
            </div>


            <div class="divide">
                <p class="mshd-1">
                    <i class="material-icons md-42">keyboard_arrow_down</i>
                </p>
            </div>


            <!-- LIST PF PRODUCTS -->
            <div class="head-text mb12">
                <p>Alle Produkte</p>
            </div>

            <div class="product-overview">

                <?php

                // MAKE ARRAY OF PRODUCT IDS
                $pids = [];
                $sel = $pdo->prepare("SELECT * FROM customer_buys_products WHERE bid = ?");
                $sel->execute([$o->oid]);

                foreach ($sel->fetchAll() as $p) {

                    $pids[] = $p->pid;
                }

                // LOOP ALL PRODUCTS
                foreach ($pids as $pid) {

                    $sel = $pdo->prepare("
                        SELECT * 
                        FROM products, products_images
                        WHERE products.id = ?
                        AND products.id = products_images.pid
                        AND products_images.isgal = '1'
                    ");
                    $sel->execute([$pid]);

                    foreach ($sel->fetchAll() as $prd) {

                ?>

                        <div class="item lt posrel">
                            <div style="z-index:1;position:absolute;top:0;left:0;height:100%;width:100%;border-radius:6px;background: rgb(0,0,0);background: linear-gradient(180deg, rgba(0,0,0,0) 67%, rgba(0,0,0,0.5032387955182073) 100%);">
                                <p class="posabs" style="text-shadow:0 1px 4px rgba(0,0,0,.32);right:24px;bottom:24px;color:white;font-size:1.2em;font-weight:600;">EUR <?php echo number_format($prd->price, 2, ',', '.'); ?></p>
                            </div>
                            <div class="actual-image mshd-1">
                                <img class="vishid opa0" onload="fadeIn(this)" src="<?php echo $url["img"] . '/products/' . $prd->url; ?>">
                            </div>
                        </div>

                <?php

                    }
                }

                ?>

                <div class="cl"></div>
            </div>

            <div class="divide">
                <p class="mshd-1">
                    <i class="material-icons md-42">keyboard_arrow_down</i>
                </p>
            </div>

            <!-- CUSTOMER INFORMATION -->
            <div class="head-text mb12">
                <p>Kundeninformationen</p>
            </div>

            <div class="user-field">

                <div class="disfl fldirrow">

                    <content-card class="half">
                        <div class="adjust mshd-1 bg-blue-green">
                            <div class="icon">
                                <p>
                                    <i class="material-icons em-10">account_circle</i>
                                </p>
                            </div>
                            <div>

                                <div class="colorfields-inr">
                                    <div class="type">
                                        <p>Kunde</p>
                                    </div>

                                    <div class="normal-field">
                                        <p><?php echo $adr->fullname; ?></p>
                                    </div>


                                    <div class="type mt12">
                                        <p>Adresse</p>
                                    </div>

                                    <div class="normal-field">
                                        <p>
                                            <?php
                                            if ($adr->additional !== 'none') {
                                                echo $adr->address . ', ' . $adr->additional;
                                            } else {
                                                echo $adr->address;
                                            }
                                            ?>
                                        </p>
                                    </div>

                                    <div class="normal-field">
                                        <p><?php echo $adr->city . ', ' . $adr->postcode; ?></p>
                                    </div>

                                    <?php if (strlen($adr->tel) > 0) { ?>
                                        <div class="type mt12">
                                            <p>Kontakt</p>
                                        </div>

                                        <div class="normal-field">
                                            <p><?php echo $adr->tel; ?></p>
                                        </div>
                                    <?php } ?>

                                </div>


                            </div>
                        </div>
                    </content-card>

                    <content-card class="half">
                        <div class="adjust mshd-1 bg-orange-purple">
                            <div class="icon">
                                <p>
                                    <i class="material-icons em-10">credit_card</i>
                                </p>
                            </div>
                            <div>

                                <div class="colorfields-inr">
                                    <div class="type">
                                        <p>Kontoinhaber</p>
                                    </div>

                                    <div class="normal-field">
                                        <p><?php echo $pmm->account; ?></p>
                                    </div>


                                    <div class="type mt12">
                                        <p>BIC</p>
                                    </div>

                                    <div class="normal-field">
                                        <p><?php echo $pmm->bic; ?></p>
                                    </div>

                                    <div class="type mt12">
                                        <p>IBAN</p>
                                    </div>

                                    <div class="normal-field">
                                        <p>DE-<?php echo $pmm->iban; ?></p>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </content-card>

                </div>

            </div>

            <div class="bottom-distance"></div>

        </wide-container>

<?php

    }
} else {
    exit("0");
}


?>