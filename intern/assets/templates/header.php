<header class="menu">

    <div class="logo">
        <img src="<?php echo $imgurl; ?>/global/logo-green.svg">
    </div>
   
    <div class="inr">
       
        <div class="ih">
            <p>Inhaltsverzeichnis</p>
        </div>

        <ul class="main-menu">
            <a href="<?php echo $inturl; ?>/disclaimer">
                <li class="tran <?php if($pid === 'intern:disclaimer') echo 'active'; ?>">
                    <p class="tran">Datenschutz</p>
                </li>
            </a>

            <a href="<?php echo $inturl; ?>/imprint">
                <li class="tran <?php if($pid === 'intern:imprint') echo 'active'; ?>">
                    <p class="tran">Impressum</p>
                </li>
            </a>

            <a href="<?php echo $inturl; ?>/sepa">
                <li class="tran <?php if($pid === 'intern:sepa') echo 'active'; ?>">
                    <p class="tran">SEPA-Lastschrift</p>
                </li>
            </a>

        </ul>

        <div class="ih" style="margin-top:32px;">
            <p>Weiteres</p>
        </div>

        <ul class="main-menu">
            <a href="<?php echo $purl; ?>">
                <li class="tran">
                    <p class="tran">Zurück zum Shop</p>
                </li>
            </a>

        </ul>
    </div>

</header>