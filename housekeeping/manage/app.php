<?php

require_once "../../mysql/_.session.php";

if ($loggedIn) {
    if ($user['admin'] !== '1') {
        header('location: /oopsie');
    }
} else {
    header('location: /oopsie');
}

$ptit = 'Manage: Shop';
$pid = "manage:app";

include_once "../assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once "../assets/templates/menu.php"; ?>

<main-content>

    <!-- MC: HEADER -->
    <?php include_once "../assets/templates/header.php"; ?>


    <!-- MC: CONTENT -->
    <div class="mc-main">
        <div class="wide">

            <?php

            // GET WEB SETTINGS
            $sel = $c->prepare("SELECT * FROM web_settings WHERE id = ?");
            $sel->bind_param('s', $config["sys_set_id"]);
            $sel->execute();
            $sel_r = $sel->get_result();
            $sel->close();

            $s = $sel_r->fetch_assoc();

            ?>



            <!-- ATTENTION -->
            <div class="mm-heading mb12">
                <p class="title lt lh42">Achtung!</p>
            </div>

            <content-card>
                <div class="adjust apps hd-shd">

                    <div class="inr">

                        <p class="fw4">Nehme verschiedenste Einstellungen zu der Applikation vor. Diese Einstellungen sollten einem administrierenden Entwickler vorbehalten werden, da sie Sektionen der gesamten Webpräsenz verändern und bei falscher Bearbeitung Probleme erzeugen können.</p>

                    </div>
                </div>

            </content-card>




            <style>
                input:focus {
                    border: 0 !important;
                    border-bottom: 1px solid #D30C42 !important;
                }
            </style>


            <!-- DISPLAY -->
            <div class="mm-heading mb12 mt32">
                <p class="title lt lh42">Display</p>
            </div>

            <form id="data-form-manage-app-display" data-form="manage:app,display">

                <content-card class="mb42">
                    <div class="adjust apps hd-shd">

                        <div class="inr">


                            <div class="boolean-input">

                                <div class="fw6 mb8">
                                    <p style="color:#5068A1;">PHP Error Reporting</p>
                                </div>

                                <div class="desc-bool">

                                    <p class="lt text">Schaltet das Error-Reporting durch PHP ein. Dies sollte nur zu Debugging-Zwecken aktiviert werden.</p>

                                    <div class="bool rt">
                                        <div class="boolean-great <?php if ($s['displayerrors'] === '1') echo 'on'; ?>" data-element="boolean-great" data-action="manage:app,display">
                                            <div class="outer tran-all">
                                                <div class="actual mshd-1 tran-all-cubic">
                                                    <div class="booler"></div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="displayerrors" value="<?php echo $s['displayerrors']; ?>">
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                            </div>

                            <div class="boolean-input mt32">

                                <div class="fw6 mb8">
                                    <p style="color:#5068A1;">Wartungsmodus</p>
                                </div>

                                <div class="desc-bool">

                                    <p class="lt text">Aktiviert den allgemeinen Wartungsmodus. Dieser sollte nur eingeschaltet werden, sofern Funktionen oder andere essenzielle Seiten-Bausteine getestet werden müssen.</p>

                                    <div class="bool rt">
                                        <div class="boolean-great <?php if ($s['maintenance'] === '1') echo 'on'; ?>" data-element="boolean-great" data-action="manage:app,display">
                                            <div class="outer tran-all">
                                                <div class="actual mshd-1 tran-all-cubic">
                                                    <div class="booler"></div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="maintenance" value="<?php echo $s['maintenance']; ?>">
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                            </div>

                            <div class="posrel disn">
                                <!-- Date -->
                                <div class="lt" style="width:calc(50% - 6px);">
                                    <div class="desc">
                                        <p class="title">PHP Errors</p>
                                    </div>

                                    <div data-element="boolean" class="boolean" data-action="manage:app,display" data-display="errors">
                                        <div class="boolean-outer">
                                            <div data-json='[{"turn":"1"}]' class="lt bool tran-all <?php if ($s['displayerrors'] === '1') {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                                <p class="tac ttup">Ein</p>
                                            </div>
                                            <div data-json='[{"turn":"0"}]' class="lt tran-all bool <?php if ($s['displayerrors'] === '0') {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                                <p class="tac ttup">Aus</p>
                                            </div>

                                            <div class="cl"></div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Version -->
                                <div class="rt" style="width:calc(50% - 6px);">
                                    <div class="desc">
                                        <p class="title">Wartungsmodus</p>
                                    </div>

                                    <div data-element="boolean" class="boolean" data-action="manage:app,display" data-display="maintenance">
                                        <div class="boolean-outer">
                                            <div data-json='[{"turn":"1"}]' class="lt tran-all bool <?php if ($s['maintenance'] === '1') {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                                <p class="ttup tac">Aktiv</p>
                                            </div>
                                            <div data-json='[{"turn":"0"}]' class="lt tran-all bool <?php if ($s['maintenance'] === '0') {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                                <p class="tac ttup">Inaktiv</p>
                                            </div>

                                            <div class="cl"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cl"></div>
                            </div>

                        </div>

                    </div>
                </content-card>

            </form>


            <!-- GENERAL -->
            <div class="mm-heading mb12 mt42">
                <p class="title lt lh42">Allgemein</p>
            </div>

            <form data-form="manage:app">

                <content-card>
                    <div class="adjust apps hd-shd">

                        <div class="inr">

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Shop-Name</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['name']; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="mb32">
                                <!-- Date -->
                                <div class="lt" style="width:calc(50% - 6px);">
                                    <div class="desc">
                                        <p class="title">Aktuelles Jahr</p>
                                    </div>

                                    <div class="input">
                                        <div class="input-outer">
                                            <input style="border-radius:0px;" class="tran-all" name="date" type="text" placeholder="<?php echo $s['date']; ?>">
                                        </div>
                                    </div>
                                </div>


                                <!-- Version -->
                                <div class="rt" style="width:calc(50% - 6px);">
                                    <div class="desc">
                                        <p class="title">App-Version</p>
                                    </div>

                                    <div class="input">
                                        <div class="input-outer">
                                            <input style="border-radius:0px;" class="tran-all" name="version" type="text" placeholder="<?php echo $s['version']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="cl"></div>
                            </div>


                            <div>
                                <!-- Date -->
                                <div class="lt" style="width:calc(50% - 6px);">
                                    <div class="desc">
                                        <p class="title">Aktuelle Mehrwert-Steuer</p>
                                    </div>

                                    <div class="input">
                                        <div class="input-outer">

                                            <div style="color:green;right:18px;padding-left:12px;line-height:32px;top:5px;font-size:1.2em;border-left:1px solid rgba(0,0,0,.12);" class="fw6 posabs">
                                                <p>%</p>
                                            </div>

                                            <input style="border-radius:0px;" class="tran-all" name="mwstr" type="text" placeholder="<?php echo $s['mwstr']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="cl"></div>
                            </div>



                        </div>

                    </div>
                </content-card>


                <!-- GENERAL -->
                <div class="mm-heading mb12 mt42">
                    <p class="title lt lh42">Linking</p>
                </div>

                <content-card class="mb42">
                    <div class="adjust apps hd-shd">

                        <div class="inr">

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Shop-URL</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Intern (Data Privacy Policies, Imprint)</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_intern']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Mobil</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_mobile']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Dashboard</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_hk']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Error-Seite</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_error']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">CSS-Basis</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_css']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Script-Basis</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_js']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Image-Basis</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_img']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb32">
                                <div class="desc">
                                    <p class="title">Icon-Basis</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['url_icons']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class>
                                <div class="desc">
                                    <p class="title">Upload direction</p>
                                </div>

                                <div class="input">
                                    <div class="input-outer">
                                        <input style="border-radius:0px;" class="tran-all" name="name" type="text" placeholder="<?php echo $s['dir_uploads']; ?>">
                                    </div>
                                </div>
                            </div>



                        </div>

                    </div>
                </content-card>


            </form>

        </div>
    </div>
</main-content>