<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'scard-overview') {

    $uid = $my->id;

?>

    <div class="title">
        <p>Übersicht</p>
    </div>

    <input type="hidden" data-name="products" value='<?php

                                                        // get üroducts
                                                        $getProducts = $pdo->prepare("SELECT * FROM shopping_card WHERE uid = ?");
                                                        $getProducts->execute([$uid]);
                                                        $pNum = $getProducts->rowCount();

                                                        $counter = 0;

                                                        foreach ($getProducts->fetchAll() as $p) {
                                                            if (++$counter !== $pNum) {
                                                                $sppid = $p->pid;
                                                                echo "$sppid,";
                                                            } else {
                                                                $sppid = $p->pid;
                                                                echo "$sppid";
                                                            }
                                                        }

                                                        ?>'>

    <form data-form="scard">

        <div class="payment-method">
            <div class="tit mb8">
                <p>Zahlungsmethode</p>
            </div>

            <?php if ($my->billingPreference) { ?>
                <div data-element="select" data-action="accounts-scard" class="mb12">
                    <div class="select rd3 mshd-1">
                        <p class="lt"><i class="icon-credit-card"></i> &nbsp;&nbsp; Bankeinzug</p>
                        <p class="ml8 rt"><i class="icon-down-open-1"></i></p>

                        <div class="cl"></div>
                    </div>

                    <div class="list mshd-2 rd3 tran-all-cubic">
                        <ul>
                            <li data-json='[{"which":"bank"}]'><i class="icon-credit-card"></i> &nbsp;&nbsp; Vorkasse</li>
                        </ul>
                    </div>
                </div>

                <div data-react="account-scard" class="vishid opa0 hw1 tran-all"></div>
            <?php } else { ?>

                <div data-action="add-payment-method" class="hellofresh hlf-white mshd-1 rd3">
                    <div class="disfl fldirrow">
                        <p class="ml6 mr14"><i class="icon-credit-card"></i></p>
                        <p class="trimfull">Bankverbindung hinzufügen</p>
                    </div>
                </div>

            <?php } ?>

        </div>


        <!-- ADDRESS -->
        <div class="address mt24">
            <div class="tit mb8">
                <p>Lieferungsoptionen</p>
            </div>

            <?php

            // get customer's addresses
            $getAddresses = $pdo->prepare("SELECT * FROM customer_addresses WHERE uid = ?");
            $getAddresses->execute([$uid]);

            if ($getAddresses->rowCount() >= 1) {

            ?>

                <div data-element="select" data-action="address-scard" class="mb8">
                    <div class="select rd3 mshd-1">
                        <p class="lt trimfull"><i class="icon-home"></i> &nbsp;&nbsp; Adresse</p>
                        <p class="ml8 rt"><i class="icon-down-open-1"></i></p>

                        <div class="cl"></div>
                    </div>

                    <div data-select="addresses" class="list multi mshd-2 rd3 tran-all-cubic">
                        <ul>
                            <?php foreach ($getAddresses->fetchAll() as $a) { ?>

                                <li data-json='[{"id":"<?php echo $a->id; ?>"}]'>
                                    <div class="icon lt">
                                        <p><i class="icon-home"></i></p>
                                    </div>
                                    <div class="list-title rt">
                                        <div class="trimfull name">
                                            <p><?php echo $a->fullname; ?></p>
                                        </div>
                                        <div class="under">
                                            <p class="trimfull address"><?php echo $a->address; ?></p>
                                        </div>
                                        <div class="under">
                                            <p class="trimfull city"><?php echo $a->postcode; ?> <?php echo $a->city; ?></p>
                                        </div>
                                        <div class="under">
                                            <p class="trimfull tel"><?php echo $a->tel; ?></p>
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </li>

                            <?php } ?>
                        </ul>
                    </div>

                    <input type="hidden" name="address" value="">
                </div>

            <?php } else { ?>

                <div data-action="add-address" class="hellofresh hlf-white mshd-1 rd3">
                    <div class="disfl fldirrow">
                        <p class="ml6 mr14"><i class="icon-home"></i></p>
                        <p class="trimfull mr12">Adresse hinzufügen</p>
                    </div>
                </div>

            <?php }  ?>

        </div>

        <!-- DELIVERY -->
        <div class="address mt12">

            <div data-element="select" data-action="address-scard" class="mb8">
                <div class="select rd3 mshd-1">
                    <p class="lt trimfull"><i class="icon-truck"></i> &nbsp;&nbsp; Versandart</p>
                    <p class="ml8 rt"><i class="icon-down-open-1"></i></p>

                    <div class="cl"></div>
                </div>

                <div data-select="delivery" class="list multi mshd-2 rd3 tran-all-cubic">
                    <ul>
                        <li data-json='[{"type":"combi"}]'>
                            <div class="icon lt">
                                <p><i class="icon-truck"></i></p>
                            </div>
                            <div class="list-title rt">
                                <div class="trimfull name">
                                    <p>Kombiversand</p>
                                </div>
                                <div class="under">
                                    <p class="trimfull address">Ab 6 €</p>
                                </div>
                            </div>

                            <div class="cl"></div>
                        </li>
                        <li data-json='[{"type":"single"}]'>
                            <div class="icon lt">
                                <p><i class="icon-truck"></i></p>
                            </div>
                            <div class="list-title rt">
                                <div class="trimfull name">
                                    <p>Einzelversand</p>
                                </div>
                                <div class="under">
                                    <p class="trimfull address">Je Produkt 4 €</p>
                                </div>
                            </div>

                            <div class="cl"></div>
                        </li>
                    </ul>
                </div>

                <input type="hidden" name="delivery" value="">
            </div>

        </div>

        <!-- PRICE -->
        <div data-react="pricing" class="price mt12 mb12"></div>

        <!-- TOOLS -->
        <div class="toolery">
            <div data-react="pricing-hint" class="mt14" style="font-size:.8em;color:#999;">
                <p>Wähle alle Einstellungen, um Kosten zu berechnen</p>
            </div>

            <button type="button" data-react="select-scard" class="hellofresh hlf-brown w100 disfl fldirrow jstfycc mt12 tran-all rd3 curpo" disabled="disabled">
                <p class="trimfull">Bestellung aufgeben</p>
            </button>
        </div>

    </form>

    <script>
        $(function() {

            <?php if ($my->billingPreference) { ?>
                let which;

                <?php

                if ($bp->payment === 'bank') {
                    echo "which = 'bank';";
                } else {
                    echo "which = 'paypal';";
                }

                ?>

                var clickPaymentMethod = $('[data-action="accounts-scard"]').find('.list ul li[data-json=\'[{"which":"' + which + '"}]\']').click();
            <?php } ?>

            <?php if ($my->addressPreference) { ?>
                var adid = '<?php echo $ap->adid; ?>';
                var clickAddress = $('[data-action="address-scard"]').find('.list ul li[data-json=\'[{"id":"' + adid + '"}]\']').click();
            <?php } ?>

        });
    </script>

<?php

} else {
    exit("0");
}

?>