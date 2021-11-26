<p class="fw7 mt12 mb24" style="font-size:1.6em;line-height:1.3;">Kurse</p>
<div class="button-outer mb12 mt24 posrel">

    <?php

    $getCourses = $pdo->prepare("
            SELECT * 
            FROM courses 
            WHERE deleted != '1' 
            ORDER BY id
        ");
    $getCourses->execute();

    foreach ($getCourses->fetchAll() as $c) {

    ?>

        <a href="/course/<?php echo $c->id; ?>">
            <div class="button tac">
                <p class="trimfull"><?php echo $c->name; ?></p>
            </div>
        </a>

    <?php } ?>

    <a href="/course/project">
        <div class="button tac">
            <p class="trimfull">Projektbegleitung</p>
        </div>
    </a>


    <div style="margin-top:32px;font-weight:700;font-size:1.2em;margin-bottom:12px;">Interessiert?</div>
    <a href="/course/signup">
        <div class="button tac" style="background:green">
            <p class="trimfull">Infos zur Anmeldung</p>
        </div>
    </a>


</div>
<div class="cl"></div>