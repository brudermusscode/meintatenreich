<?php

    require_once "../../../../../../mysql/_.session.php";

    if(isset($_REQUEST['id'], $_REQUEST['date'], $_REQUEST['start'], $_REQUEST['end'])) {
  
        $id = htmlspecialchars($_REQUEST['id']);
        $date = htmlspecialchars($_REQUEST['date']);
        $start = htmlspecialchars($_REQUEST['start']);
        $end = htmlspecialchars($_REQUEST['end']);
        
?>

        <content-card class="mb8 posrel fall-in tran-all-cubic">
            <div class="mshd-1 normal-box">
                <div style="padding:18px 42px;">
                
                    <div class="lt mr42">
                        <p class="ttup fs12 c9 mb8">Datum</p>
                        <div class="lt lh24">
                            <p class="lt c3 mr4"><i class="material-icons md24">event</i></p>
                            <p class="lt c3"><?php echo $date; ?></p>
                            
                            <div class="cl"></div>
                        </div>
                    </div>
                
                    <div class="rt">
                        <div class="lt mr24">
                            <p class="ttup fs12 c9 mb8">Start</p>
                            <div class="lt lh24">
                                <p class="lt c3"><?php echo $start; ?></p>

                                <div class="cl"></div>
                            </div>
                        </div>

                        <div class="lt">
                            <p class="ttup fs12 c9 mb8">Ende</p>
                            <div class="lt lh24">
                                <p class="lt c3"><?php echo $end; ?></p>

                                <div class="cl"></div>
                            </div>
                        </div>
                
                        <div class="cl"></div>
                    </div>
                        
                    <div class="cl"></div>
                </div>
            </div>
        </content-card>
  
<?php
        
    } else {
        exit('fuck you');
    }

?>