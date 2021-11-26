<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Übersicht";
$pid = "profile";
$rgname = 'Warenkorb';

if (!$loggedIn) {
    header('location: ../oops');
}

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">


            <?php include_once $sroot . "/assets/templates/customers/menu.php"; ?>


            <div class="lt">
                <div class="ph42">
                    <div class="kartei-quer">
                        <div class="justify">
                            <div class="edit-overview">
                                <button class="btn-rd-ct-shd-rt" data-action="open-settings">
                                    <p><i class="icon-cog"></i></p>
                                </button>
                            </div>
                            <div class="category" style="padding-top:0px;">
                                <p>Anzeigename</p>
                                <p class="trimfull"><?php echo $my->displayname; ?></p>
                            </div>
                            <div class="category">
                                <p>Vor- & Nachname</p>
                                <?php if ($my->secondname === '' && $my->firstname === '') { ?>
                                    <p class="link" data-action="open-settings" data-json='[{"which":"set-name"}]'><i class="icon-plus-1"></i> Name hinzufügen</p>
                                <?php } else { ?>
                                    <p><?php echo $my->firstname; ?> <?php echo $my->secondname; ?></p>
                                <?php } ?>
                            </div>
                            <div class="category">
                                <p>E-Mail Adresse
                                    <?php if ($my->verified === '0') { ?>
                                        <span style="color:red">
                                            &nbsp; (<i class="icon-cancel-circled"></i> Nicht verifiziert)
                                        <?php } else { ?>
                                            <span style="color:green">
                                                &nbsp; <i class="icon-ok"></i>
                                            <?php } ?></span>
                                </p>
                                <p class="trimfull"><?php echo $my->mail; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cl"></div>
        </div>
    </div>
</div>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>