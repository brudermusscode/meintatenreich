<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";


if (isset($_REQUEST['id']) && $admin->isAdmin()) {

    $oid = $_REQUEST['id'];

    // CHECK IF ORDER EXISTS
    $sel = $pdo->prepare("
        SELECT * 
        FROM courses, courses_content 
        WHERE courses.id = courses_content.cid 
        AND courses.id = ? 
        LIMIT 1
    ");
    $sel->execute([$oid]);


    if ($sel->rowCount() > 0) {

        // FETCH COURSE
        $o = $sel->fetch();

        // CONVERT TIMESTAMP
        $timeAgoObject = new convertToAgo;
        $ts = $o->timestamp;
        $convertedTime = ($timeAgoObject->convert_datetime($ts));
        $when = ($timeAgoObject->makeAgo($convertedTime));

?>

        <wide-container style="padding-top:62px;" data-json='[{"id":"<?php echo $o->id; ?>"}]'>


            <!-- INFORMATON BOX -->
            <div class="head-text mb12">
                <p>Kurs verwalten</p>
            </div>


            <form data-form="manage:courses,edit" method="POST" action>

                <content-card class="mb24 posrel">
                    <div class="mshd-1 normal-box">
                        <div style="padding:28px 42px;">

                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Kursname</p>
                            </div>

                            <div class="input tran-all-cubic mb42">
                                <div class="input-outer">
                                    <input type="text" autocomplete="off" name="name" placeholder="Wie möchtest du den Kurs nennen?" value="<?php echo $o->name; ?>" class="tran-all">
                                </div>
                            </div>

                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Beschreibung</p>
                            </div>

                            <div class="textarea mb42">
                                <div class="textarea-outer">
                                    <textarea name="content" placeholder="Gebe eine genaue Beschreibung zu deinem Kurs an..." class="tran-all"><?php echo $o->content; ?></textarea>
                                </div>
                            </div>


                            <div style="width:calc(50% - 12px);" class="lt">
                                <div class="fw6 mb12">
                                    <p style="color:#5068A1;">Max. Teilnehmer-Zahl</p>
                                </div>

                                <div class="input tran-all-cubic mb62">
                                    <div class="input-outer">
                                        <div style="color:rgba(166,88,246,1);right:18px;padding-left:12px;line-height:42px;height:32px;top:5px;font-size:1.2em;border-left:1px solid rgba(0,0,0,.12);" class="fw6 posabs">
                                            <p><i class="material-icons md-24">person</i></p>
                                        </div>
                                        <input type="text" autocomplete="off" name="size" placeholder="z. B. 15..." class="tran-all" value="<?php echo $o->size; ?>" style="padding-right:62px;width:calc(100% - 32px - 62px);">
                                    </div>
                                </div>
                            </div>


                            <div style="width:calc(50% - 12px);" class="rt">
                                <div class="fw6 mb12">
                                    <p style="color:#5068A1;">Preis</p>
                                </div>

                                <div class="input tran-all-cubic mb62">
                                    <div class="input-outer">
                                        <div style="color:green;right:18px;padding-left:12px;line-height:32px;top:5px;font-size:1.2em;border-left:1px solid rgba(0,0,0,.12);" class="fw6 posabs">
                                            <p>€</p>
                                        </div>
                                        <input type="text" autocomplete="off" name="price" placeholder="Preis in EURO..." class="tran-all" value="<?php echo number_format($o->price, 2, ',', '.'); ?>" style="padding-right:62px;width:calc(100% - 32px - 62px);">
                                    </div>
                                </div>
                            </div>

                            <div class="cl"></div>


                            <style>
                                .boolean-great .outer {
                                    background: rgba(0, 0, 0, .24);
                                    border-radius: 100px;
                                    width: 60px;
                                    height: 32px;
                                    position: relative;
                                    cursor: pointer;
                                }

                                .boolean-great.on .outer {
                                    background: rgba(16, 155, 27, 0.24);
                                }

                                .boolean-great .outer .actual {
                                    height: calc(100% - 4px);
                                    width: 28px;
                                    background: white;
                                    border-radius: 50%;
                                    position: absolute;
                                    top: 2px;
                                    left: 2px;
                                }

                                .boolean-great.on .outer .actual {
                                    left: calc(100% - 2px);
                                    transform: translateX(-100%);
                                }

                                .boolean-input {
                                    width: 100%;
                                }

                                .boolean-input .desc-bool {
                                    position: relative;
                                }

                                .boolean-input .desc-bool .text {
                                    color: #999;
                                    width: calc(100% - 84px);
                                }

                                .boolean-input .desc-bool .bool {
                                    width: 60px;
                                }
                            </style>

                            <!-- ACTIVE -->
                            <div class="boolean-input mt32">

                                <div class="fw6 mb8">
                                    <p style="color:#5068A1;">Verfügbarkeit</p>
                                </div>

                                <div class="desc-bool">

                                    <p class="lt text">Verwalte und stelle ein, ob dieser Kurs im Moment angeboten wird oder nicht.</p>

                                    <div class="bool rt">
                                        <div class="boolean-great <?php if ($o->active === '1') {
                                                                        echo 'on';
                                                                    } ?>" data-element="boolean-great">
                                            <div class="outer tran-all">
                                                <div class="actual mshd-1 tran-all-cubic">
                                                    <div class="booler"></div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="active" value="<?php echo $o->active; ?>">
                                        </div>
                                    </div>

                                    <div class="cl"></div>
                                </div>

                            </div>


                            <button type="submit" data-action="manage:course,edit,save" class="btn-outline rt mt32" style="border-color:#AC49BD;color:#AC49BD;">
                                <p>Speichern</p>
                            </button>

                            <div class="cl"></div>

                        </div>
                    </div>
                </content-card>

            </form>

            <div class="bottom-distance"></div>

        </wide-container>

<?php

    }
} else {
    exit;
}


?>