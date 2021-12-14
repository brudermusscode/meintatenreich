<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if (!$admin->isAdmin()) {
    header('location: /oops');
}

$ptit = 'Changelog';
$pid = "overview:changelog";

include_once $sroot . "/housekeeping/assets/templates/head.php";

?>

<!-- MAIN MENU -->
<?php include_once $sroot . "/housekeeping/assets/templates/menu.php"; ?>


<main-content class="overview">

    <!-- MAIN HEADER -->
    <?php include_once $sroot . "/housekeeping/assets/templates/header.php"; ?>

    <!-- MAIN CONTENT -->
    <div class="mc-main">

        <!-- MC: LEFT -->
        <div class="lt left-content">

            <style>
                .chl-head {
                    display: flex;
                    flex-direction: row;
                    padding-left: 24px;
                    margin-bottom: 12px;
                }

                .chl-head .title {
                    padding-top: 6px;
                    font-weight: 700;
                    margin-left: 20px;
                    font-size: 1.2em;
                }

                .chl-box {
                    background: white;
                    padding: 28px 32px 24px;
                    border-radius: 12px;
                    margin-top: 6px;
                }

                .chl-box--p {
                    display: flex;
                    flex-direction: row;
                    font-size: 1em;
                    line-height: 1;
                }

                .chl-box--p .icon {
                    margin-right: 12px;
                }
            </style>

            <div class="mm-content">

                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">shopping_bag</i></p>
                        <p class="title">Shop</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Ästhetische Veränderungen/Verbesserungen</strong></p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Angepasste Anzeige des Shop-Kastens auf verschiedenen Bildschirmgrößen (Fehler wurden hier behoben)</strong></p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Migration von PHP 7.4 (Plain) auf <strong>PHP 8.0 (Library PDO)</strong></p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Passwort-Verschlüsselung nach <strong>ARGON2ID hashing</strong></p>
                        </div>

                    </div>

                </div>

                <div class="chl slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">shopping_bag</i></p>
                        <p class="icon"><i class="material-icons md-32 mr24 ml24">keyboard_arrow_right</i></p>
                        <p class="icon"><i class="material-icons md-32">account_circle</i></p>
                        <p class="title">Mein Konto</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verbesserte Profil-Verifizierung</strong></p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Optimierte Bestellerfahrung für den Kunden</strong></p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Änderung von Passwörtern implementiert</strong></p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Optimierte Verifizierung von Adressen und Bankdaten</strong></p>
                        </div>

                    </div>

                </div>

                <div style="border-bottom:1px solid rgba(0,0,0,.06);height:1px;margin-bottom:62px;margin-top:62px;margin-left:82px;margin-right:82px;"></div>

                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">space_dashboard</i></p>
                        <p class="title">Dashboard</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Neue Benachrichtigungs-Anzeige, welche genauere Informationen zum Bereich und der genauen Änderung anzeigt</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verbesserte Filter-Funktion auf jeglichen Seiten</p>
                        </div>

                    </div>

                </div>

                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">space_dashboard</i></p>
                        <p class="icon"><i class="material-icons md-32 mr24 ml24">keyboard_arrow_right</i></p>
                        <p class="icon"><i class="material-icons md-32">inbox</i></p>
                        <p class="title">Nachrichten</p>
                    </div>

                    <div class="chl-box hd-shd-1">
                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verbesserter Single-Mailer</p>
                        </div>
                    </div>

                </div>

                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">space_dashboard</i></p>
                        <p class="icon"><i class="material-icons md-32 mr24 ml24">keyboard_arrow_right</i></p>
                        <p class="icon"><i class="material-icons md-32">euro</i></p>
                        <p class="title">Bestellungen</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verwaltung von Bestellung verbessert</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Benachrichtigungs-E-Mails für jede Status-Änderung</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verbesserter Bestellprozess</p>
                        </div>

                    </div>

                </div>

                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">space_dashboard</i></p>
                        <p class="icon"><i class="material-icons md-32 mr24 ml24">keyboard_arrow_right</i></p>
                        <p class="icon"><i class="material-icons md-32">casino</i></p>
                        <p class="title">Produkte</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Ästhetische Änderungen</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verbesserter Upload von Bildern</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Archivierungs-Funktion</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Deaktivierungs-Funktion</p>
                        </div>

                    </div>

                </div>

                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">space_dashboard</i></p>
                        <p class="icon"><i class="material-icons md-32 mr24 ml24">keyboard_arrow_right</i></p>
                        <p class="icon"><i class="material-icons md-32">golf_course</i></p>
                        <p class="title">Kurse</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Ästhetische Änderungen</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verbesserter Berechnung von kommenden und vergangenen Terminen</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Auswahl von Datum und Zeit für neue Termine vereinfacht</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Automatisches Archivieren von vergangenen Terminen</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Verbesserte Deaktivierungs-Funtion für Kurse</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Archivierungs-Funktion für Kurse</p>
                        </div>

                    </div>

                </div>


                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">space_dashboard</i></p>
                        <p class="icon"><i class="material-icons md-32 mr24 ml24">keyboard_arrow_right</i></p>
                        <p class="icon"><i class="material-icons md-32">data_usage</i></p>
                        <p class="title">phpMyAdmin</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Datenbank wurde aufgeräumt</p>
                        </div>

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Automatisch gesetzte Zeitstempel</p>
                        </div>

                    </div>

                </div>


                <div class="chl mb42 slideUp">

                    <div class="chl-head">
                        <p class="icon"><i class="material-icons md-32">space_dashboard</i></p>
                        <p class="icon"><i class="material-icons md-32 mr24 ml24">keyboard_arrow_right</i></p>
                        <p class="icon"><i class="material-icons md-32">mark_as_unread</i></p>
                        <p class="title">Mailer</p>
                    </div>

                    <div class="chl-box hd-shd-1">

                        <div class="chl-box--p">
                            <div class="icon"><i class="material-icons md-24">keyboard_arrow_right</i></div>
                            <p class="pt4">Ästhetische Änderungen</p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="cl"></div>
    </div>
</main-content>


<?php include_once $sroot . "/housekeeping/assets/templates/footer.php"; ?>