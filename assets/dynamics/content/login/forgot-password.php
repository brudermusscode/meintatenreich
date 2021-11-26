<?php

    if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'forgot-password') {
        
?>

        <wide-container class="almid posabs">
            <div class="mshd-2 rd5 zoom-in bgf">

                <div class="close tran-all" data-action="close-overlay">
                    <p><i class="icon-cancel-5"></i></p>
                </div>

                <div class="body">
                    <div class="form-outer">
                        <form data-form="fgp">

                            <p class="c9 mb24" style="word-wrap:break-word;font-size:.8em;">Gib Deine E-Mail Adresse unten ein und warte auf eine Bestätigung in deinem Postfach. Dort ist ein Link zum Zurücksetzen Deines Passworts enthalten.</p>
                            
                            <div class="input">
                                <div>
                                    <p>E-Mail Adresse</p>
                                </div>
                                <div class="posrel">
                                    <input type="text" name="mail" placeholder="" class="tran-all">
                                    <div class="posabs" style="right:0;top:0;line-height:32px;width:32px;height:32px;text-align:center;">
                                        <i class="icon-mail-3"></i>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    
                    <div class="rt">
                        <button type="button" data-action="new-password" class="hellofresh hlf-pink normal rd3 mshd-1">
                            <p>Los!</p>
                        </button>
                    </div>
                    
                    <div class="cl"></div>
                </div>
                    
            </div>
        </wide-container>

<?php   
        
    } else {
        exit;
    }


?>