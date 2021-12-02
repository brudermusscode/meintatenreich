<?php


// ERROR CODE :: 0
require_once "../../../../../../mysql/_.session.php";


if ($admin->isAdmin()) {

?>

    <wide-container style="padding-top:62px;">



        <!-- GENERAL -->
        <div class="head-text mb12">
            <p>Kurs hinzufügen</p>
        </div>

        <form data-form="manage:course,add">

            <content-card class="mb42 posrel">
                <div class="mshd-1 normal-box">
                    <div style="padding:28px 42px;">

                        <input type="hidden" name="gallery" data-react="manage:products,edit,addImage,gallery" value="<?php echo $sg['id']; ?>">

                        <div class="fw6 mb12">
                            <p style="color:#5068A1;">Kursname</p>
                        </div>

                        <div class="input tran-all-cubic mb24">
                            <div class="input-outer">
                                <input type="text" autocomplete="off" name="name" placeholder="Wie möchtest du den Kurs nennen?" value class="tran-all">
                            </div>
                        </div>

                        <div class="fw6 mb12">
                            <p style="color:#5068A1;">Beschreibung</p>
                        </div>

                        <div class="textarea mb24">
                            <div class="textarea-outer">
                                <textarea name="content" placeholder="Gebe eine genaue Beschreibung zu deinem Kurs an..." class="tran-all"></textarea>
                            </div>
                        </div>


                        <div style="width:calc(50% - 12px);" class="lt mb42">
                            <div class="fw6 mb12">
                                <p style="color:#5068A1;">Kürzel</p>
                            </div>

                            <div class="input tran-all-cubic mb62">
                                <div class="input-outer">
                                    <input type="text" autocomplete="off" name="short" placeholder="3 - 4 Zeichen..." class="tran-all" value>
                                </div>
                            </div>
                        </div>

                        <div class="cl"></div>


                        <div>
                            <div style="width:calc(50% - 12px);" class="lt">
                                <div class="fw6 mb12">
                                    <p style="color:#5068A1;" class="trimfull">Max. Teilnehmer-Zahl</p>
                                </div>

                                <div class="input tran-all-cubic mb62">
                                    <div class="input-outer">
                                        <div style="color:rgba(166,88,246,1);right:18px;padding-left:12px;line-height:42px;height:32px;top:5px;font-size:1.2em;border-left:1px solid rgba(0,0,0,.12);" class="fw6 posabs">
                                            <p><i class="material-icons md-24">person</i></p>
                                        </div>
                                        <input type="text" autocomplete="off" name="size" placeholder="z. B. 15..." class="tran-all" value style="padding-right:62px;width:calc(100% - 32px - 62px);">
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
                                        <input type="text" autocomplete="off" name="price" placeholder="Preis in EURO..." class="tran-all" value style="padding-right:62px;width:calc(100% - 32px - 62px);">
                                    </div>
                                </div>
                            </div>

                            <div class="cl"></div>
                        </div>


                        <style>
                            .boolean-great {}

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

                                <p class="lt text">Verwalte und stelle ein, ob dein neuer Kurs im Moment angeboten werden soll oder nicht.</p>

                                <div class="bool rt">
                                    <div class="boolean-great on" data-element="boolean-great">
                                        <div class="outer tran-all">
                                            <div class="actual mshd-1 tran-all-cubic">
                                                <div class="booler"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="active" value="1">
                                    </div>
                                </div>

                                <div class="cl"></div>
                            </div>

                        </div>


                        <div data-action="manage:course,add,save" class="btn-outline rt mt32" style="border-color:#AC49BD;color:#AC49BD;">
                            <p>Hinzufügen</p>
                        </div>

                        <div class="cl"></div>

                    </div>
                </div>
            </content-card>

        </form>

    </wide-container>

<?php

} else {
    exit;
}

?>