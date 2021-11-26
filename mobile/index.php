<?php

    require_once "../mysql/_.session.php";
    require_once "../mysql/_.maintenance.php";

    $ptit = "Willkommen im Shop";
    $pid = "index";
    $rgname = 'Shop';

    include_once "../assets/templates/global/head.php";

?>

        <style>

            #app { width:40%;position:absolute;transform:translateX(-50%);left:50%;background:rgba(255,255,255,.28); }
            
            #m-hdr { background:white;height:auto;position:fixed;top:0;left:0;width:100%; }
            #m-hdr .hdr-inr {  }
            #m-hdr .hdr-inr .lt { padding:0; }
            #m-hdr .hdr-inr .lt .main-menu { padding:0 12px;line-height:48px;height:48px;position:relative;cursor:pointer; }
            #m-hdr .hdr-inr .lt .main-menu i { position:relative;transform:translateY(-50%);top:calc(50% - 6px); }
            #m-hdr .hdr-inr .lt .main-menu:hover { background:rgba(0,0,0,.12); }

            
        </style>
       
       
        <div id="m-hdr" class="mshd-1">
            <div class="hdr-inr">
                
                <div class="lt">
                    <div class="main-menu">
                        <i class="material-icons md24">menu</i>
                    </div>
                </div>
                
            </div>
            
        </div>


        <br>
        <br>
        <br>
        <br>

<?php

    
    include_once "../mobile/assets/templates/footer.php";

?>
