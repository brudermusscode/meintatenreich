<!-- HDR -->
<div id="hdr">

    <div class="inr">
        <div class="lt logo">
            <div data-action="shop" class="overlay"></div>
            <div class="inr-logo">
                <img class="tran-all" onload="fadeInVisOpa($(this))" src="<?php echo $url["img"]; ?>/global/logo-green.svg">
            </div>
        </div>

        <div class="lt search mt48 ml48">
            <div class="sr-outer">
                <div class="sr-icon mshd-1">
                    <i class="icon-search"></i>
                </div>
                <input data-action="search" type="text" class="mshd-1 tran-all" placeholder="Suche nach Produkten..." value="<?php if (isset($_GET['q'])) echo $_GET['q']; ?>">

                <div class="search-box mshd-2 tran-all-cubic" data-react="search"></div>
            </div>
        </div>

        <div class="rt mt56">
            <hdr-menu>
                <ul class="hm-inr">
                    <?php if ($loggedIn) { ?>
                        <li data-hover="usermenu" class="mshd-1">
                            <p class="text trim100"><?php echo $my->displayname; ?></p>
                            <p class="icon">
                                &nbsp; <i class="icon-down-open"></i>
                            </p>

                            <div class="user-dropdown mshd-3 tran-all-cubic">
                                <a href="/my/profile">
                                    <p class="um-icon">
                                        <i class="icon-user"></i>
                                    </p>
                                    <p class="um-text">Mein Konto</p>
                                </a>

                                <a href="/my/orders">
                                    <p class="um-icon">
                                        <i class="icon-shopping-bag"></i>
                                    </p>
                                    <p class="um-text">Meine Bestellungen</p>
                                </a>

                                <a href="/my/favorites">
                                    <p class="um-icon">
                                        <i class="icon-star"></i>
                                    </p>
                                    <p class="um-text">Gemerkte Produkte</p>
                                </a>

                                <a href="#" data-action="open-settings">
                                    <p class="um-icon">
                                        <i class="icon-cog"></i>
                                    </p>
                                    <p class="um-text">Einstellungen</p>
                                </a>

                                <?php if ($admin->isAdmin()) { ?>
                                    <a href="<?php echo $url["dashbrd"]; ?>/black">
                                        <p class="um-icon">
                                            <i class="icon-lock"></i>
                                        </p>
                                        <p class="um-text">Dashboard</p>
                                    </a>
                                <?php } ?>

                                <a data-action="signout" href="#">
                                    <p class="um-icon">
                                        <i class="icon-logout"></i>
                                    </p>
                                    <p class="um-text">Ausloggen</p>
                                </a>
                            </div>

                        </li>
                    <?php } ?>

                    <?php if ($loggedIn) { ?>
                        <a href="/my/shopping-card">
                        <?php } ?>
                        <li <?php if (!$loggedIn) { ?>data-action="open-login" data-json='[{"open":"login"}]' <?php } ?> class="mshd-1">
                            <p class="icon mr8">
                                <i class="icon-shopping-basket"></i>
                            </p>
                            <p>Warenkorb</p>
                            <?php if ($loggedIn) { ?>
                                <div data-react="add-scard" class="shopping-cart-amt mshd-1">
                                    <p><?php echo $_SESSION["shoppingCardAmount"]; ?></p>
                                </div>
                            <?php } ?>
                        </li>
                        <?php if ($loggedIn) { ?>
                        </a>
                    <?php } ?>


                </ul>
            </hdr-menu>
        </div>

        <div class="cl"></div>
    </div>

</div>


<!-- REGISTER -->
<div id="rgr">
    <div class="inr">

        <div class="posabs ride-00" style="background:url(<?php echo $url["img"]; ?>/global/ride00.svg) left top no-repeat;height:100px;width:188px;background-size:188px 100px;">
            <div class="adjust" style="padding:22px 28px 0 28px;transform:rotate(-4deg);">
                <p class="trimfull w100 tac"><?php echo $rgname; ?></p>
            </div>
        </div>

        <div class="lt">
            <div class="left-buttons">
                <button class="lt" data-action="shop" type="button">Shop</button>
                <a href="/course/start">
                    <button class="lt" type="button" style="margin-top:-4px;">Kursprogramme</button>
                </a>
                <a href="/contact">
                    <button class="lt" type="button" style="margin-top:-8px;">Kontakt</button>
                </a>

                <div class="cl"></div>
            </div>
        </div>

        <div class="rt">
            <div class="buttons disfl fldirrow">

                <?php if (!$loggedIn) { ?>
                    <button type="button" data-action="open-login" data-json='[{"open":"login"}]'>Einloggen</button>
                    <button type="button" data-action="open-signup" data-json='[{"open":"signup"}]'>Registrieren</button>
                <?php } ?>

            </div>
        </div>

        <div class="cl"></div>

    </div>
</div>