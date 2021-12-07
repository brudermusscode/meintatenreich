<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Kursprogramme";
$pid = "courses";
$rgname = 'Kursprogramme';


if (isset($_GET['cid'])) {

    // variablize
    $cid = preg_replace("/(.+)\.php$/", "$1", $_GET['cid']);

    switch ($cid) {
        case 'start':
        case 'project':
        case 'signup':
            $a = "b";
            break;

        default:
            // CHECK IF COURSE EXISTS
            $getCourse = $pdo->prepare("
                SELECT * 
                FROM courses, courses_content 
                WHERE courses.id = courses_content.cid
                AND courses.id = ?
            ");
            $getCourse->execute([$cid]);

            if ($getCourse->rowCount() > 0) {

                // GET COURSE INFORMATION
                $c = $getCourse->fetch();

                // GET COURSE CONTENT
                $getCourseDates = $pdo->prepare("
                    SELECT * 
                    FROM courses_dates 
                    WHERE cid = ? 
                    AND deleted != '1' 
                    AND archived != '1'
                    AND CONCAT(date, ' ', start, ':00') >= ?
                    ORDER BY CONCAT(date, ' ', start, ':00')
                    ASC
                ");
                $getCourseDates->execute([$cid, $main["fulldate"]]);
            } else {

                header('location: /oops');
            }
    }
} else {

    header('location: /oops');
}


include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<style>
    .button-outer {
        padding-left: 6px;
    }

    .button-outer .button {
        background: #B88B56;
        border-radius: 6px;
        padding: 0 24px;
        margin-bottom: 8px;
        cursor: pointer;
    }

    .button-outer .button:hover {
        opacity: .8;
    }

    .button-outer .button p {
        text-align: center;
        color: white;
        font-size: 1.2em;
        line-height: 52px;
    }
</style>

<div id="main">
    <div class="outer">
        <div class="inr">
            <div class="main-overflow-scroll w100">
                <div class="w60 lt">

                    <?php if ($cid === 'start') { ?>

                        <p class="fw7 mt12 mb24" style="font-size:1.6em;line-height:1.3;">
                            Ein neuer Kursraum & Kurse
                        </p>
                        <p class="mb32" style="font-size:1.2em;line-height:1.3;">Auf mehrfachen Kundenwunsch biete ich hier ab sofort Kurse an. Zum einen, ein Handlettering Kurs, zum anderen ein Mixed Media Kurs. Persönliche Projektbegleitung kann ebenso bei mir gebucht werden. Es gibt feste Termine oder, wenn ihr 4-6 Personen seid, können wir auch außer der Reihe einen finden.</p>

                        <p class="mt12 mb8" style="font-size:1.2em;">
                            <strong><i class="icon-direction mr8"></i>Wo finden die Kurse statt?</strong>
                        </p>
                        <p class="tac pv24 mb24" style="background:rgba(0,0,0,.08);padding-left:12px;font-size:1.2em;line-height:1.3;">
                            Windmühlenweg 33, <br>
                            49456 Bakum/Hausstette
                        </p>

                    <?php } else if ($cid === 'signup') { ?>

                        <p class="fw7 mt12 mb24" style="font-size:1.6em;line-height:1.3;">
                            Anmeldung zu Programmen
                        </p>
                        <p class="mb12" style="font-size:1.2em;line-height:1.3;">
                            <strong class="fw7">Die Termine sind nach Absprache individuell planbar, auch am Wochenende. Kontaktiert mich per Mail</strong> <a href="mailto:meintatenreich@onlinehome.de">meintatenreich@onlinehome.de</a> <strong class="fw7">und beschreibt kurz was Ihr vorhabt oder lernen möchtet.</strong>
                            <br>
                            <br>
                            Zu den Kursen, die Anmeldung kann bis zu 4 Tage vor Kursbeginn stattfinden, es sind kleine Kurse mit maximal <strong style="font-weight:700;">5 Teilnehmern</strong> geplant, damit ihr die Aufmerksamkeit bekommt, die ihr verdient! Die Kurse werden im Voraus bezahlt. Sie können bis zu 2 Tage vor Kursbeginn von euch oder von mir storniert werden!
                            <br>
                            <br>
                            <strong class="fw7">Also fix anmelden mit dem Betr. Kurs und eurem Namen und eurem Wunschtermin unter <a href="mailto:meintatenreich@onlinehome.de">meintatenreich@onlinehome.de</a> und ihr erhaltet umgehend weiteres Infomaterial.</strong>
                        </p>

                    <?php } else if ($cid === 'project') { ?>

                        <div class="w60 lt">
                            <p class="fw7 mt12 mb24" style="font-size:1.6em;line-height:1.3;">
                                Projektbegleitung
                            </p>
                            <p class="mb12" style="font-size:1.2em;line-height:1.3;">
                                Dies ist ein weiterführendes Angebot. Ihr wollt etwas Neues gestalten, ein Projekt beginnen, wisst aber nicht wie ihr es beginnen sollt? Welche Materialien passend wären? Oder ihr wollt eure Räume neu gestalten? Ein Geschenk für jemand Liebes gestalten oder etwas für Euch selbst bauen? Der Fantasie sind keine Grenzen gesetzt. Es wäre auch möglich Mixed Media & Handlettering zu vertiefen! Wir sprechen persönlich ab, was für Vorstellungen ihr habt und wie das Endresultat aussehen soll. Ich werde euch bestmöglich begleiten & stehe euch als Ansprechpartner zur Seite!
                            </p>
                        </div>

                    <?php } else { ?>

                        <p class="fw7 mt12 mb24" style="font-size:1.6em;line-height:1.3;">
                            <?php echo $c->name; ?>
                        </p>
                        <p class="mb32" style="font-size:1.2em;line-height:1.3;">
                            <?php echo $c->content; ?>
                        </p>

                        <p class="mt12 fw7" style="font-size:1.2em;line-height:1.3;">Kursgebühr</p>
                        <p style="font-size:1.2em;line-height:1.3;"><?php echo number_format($c->price, 2, ',', '.'); ?> € pro Person incl. Materialkosten</p>

                        <p class="mt12 fw7" style="font-size:1.2em;line-height:1.3;">Kursgröße</p>
                        <p style="font-size:1.2em;line-height:1.3;">Max. <?php echo $c->size; ?> Personen</p>


                        <p class="mt32 mb8" style="font-size:1.2em;">
                            <strong><i class="icon-calendar mr8"></i>Wann finden die Kurse statt?</strong>
                        </p>

                        <style>
                            .project-list .dot,
                            .project-list .odd {
                                padding: 4px 12px;
                                background: white;
                            }

                            .project-list .odd {
                                background: rgba(0, 0, 0, .12);
                            }

                            .project-list .date {
                                color: #B88B56;
                            }
                        </style>

                        <div class="project-list" style="padding:8px;padding-bottom:24px;">

                            <?php if ($getCourseDates->rowCount() < 1) { ?>

                                <p class="dot date mshd-1 mb4 rd2">
                                    Zur Zeit keine Termine
                                </p>

                                <?php

                            } else {

                                foreach ($getCourseDates->fetchAll() as $cd) {

                                ?>

                                    <p class="dot date mshd-1 mb4 rd2">
                                        <?php

                                        $newdate = date_create($cd->date);
                                        $day = $newdate->format('D');
                                        switch ($day) {
                                            case 'Mon':
                                                $day = 'Mo';
                                                break;
                                            case 'Tue':
                                                $day = 'Di';
                                                break;
                                            case 'Wed':
                                                $day = 'Mi';
                                                break;
                                            case 'Thu':
                                                $day = 'Do';
                                                break;
                                            case 'Fri':
                                                $day = 'Fr';
                                                break;
                                            case 'Sat':
                                                $day = 'Sa';
                                                break;
                                            case 'Sun':
                                                $day = 'So';
                                        }

                                        echo $day . '., ';
                                        echo '<span style="color:#333;">' . $newdate->format('d.m.Y') . ',</span> ';
                                        echo $cd->start . ' - ' . $cd->end;

                                        ?>
                                    </p>

                            <?php

                                }
                            }

                            ?>

                        </div>

                    <?php } ?>

                </div>

                <div class="w38 rt posrel">
                    <?php include_once $sroot . "/assets/templates/courses/menu.php"; ?>
                </div>

                <div class="cl"></div>

            </div>
        </div>
    </div>
</div>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>