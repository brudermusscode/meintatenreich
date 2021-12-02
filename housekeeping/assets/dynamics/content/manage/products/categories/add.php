<?php

// include everything needed to keep a session
require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

if ($admin->isAdmin()) {

?>


    <wide-container style="padding-top:62px;">


        <!-- PICTURES -->
        <div class="head-text mb12">
            <p>Kategorie hinzufügen</p>
        </div>


        <content-card class="mb42 posrel">
            <div class="mshd-1 normal-box">
                <div style="padding:32px 42px;">

                    <form data-form="manage:products,category,add" method="POST" action="" onsubmit="return false;">

                        <!-- TITLE -->
                        <div class="fw6 mb12">
                            <p style="color:#5068A1;">Name</p>
                        </div>

                        <div class="input tran-all-cubic mb32">
                            <div class="input-outer">
                                <input type="text" autocomplete="off" name="name" placeholder="Gib einen Namen für die Kategorie ein..." class="tran-all">
                            </div>
                        </div>

                        <button type="submit" class="btn-outline rt " style="border-color:#AC49BD;color:#AC49BD;">
                            <p>Hinzufügen</p>
                        </button>

                    </form>

                    <div class="cl"></div>
                </div>
            </div>
        </content-card>


    </wide-container>


<?php

} else {
    exit(0);
}

?>