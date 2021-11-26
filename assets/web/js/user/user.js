$(function(){

    let body = $("body");

    // settings popup
    $(document).on('click', '[data-action="open-settings"]', function(){
        
        let t = $(this);
        let attr = t.attr('data-json');
        let which = false;
        let url = dynamicHost + "/ajax/popups/settings.php";
        
        if(typeof attr !== typeof undefined && attr !== false) {
            let tdata = t.data('json');
            which = tdata[0].which;
        }
        
        let action = 'open-settings';
        addOverlay(body);
        let overlay = $('page-overlay');
        addLoader(overlay, 'floating');
        let loader = $('loader').parent();
        
        $.ajax({
            
            url: url,
            data: { action: action, which: which },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                loader.remove();
                overlay.append(data);
                
            }
            
        });
        
    })

    // save settings
    .on('textInput input paste', '[data-form="settings"] input', requestSettingChange);
    var changeTimer = false;
    function requestSettingChange(){
        
        let rl = $('[data-react="save-settings"]');
        rl.css('opacity', '.5').removeClass('success failure').addClass('glow');
        
        if(changeTimer !== false) clearTimeout(changeTimer);
            changeTimer = setTimeout(function(){
                
                var formData = $('[data-form="settings"]').serialize();
                var res;
                let url = dynamicHost + "/ajax/functions/user/settings";
                
                $.ajax({
                    
                    url: url,
                    data: formData,
                    method: 'POST',
                    type: 'TEXT',
                    success: function(data) {
                        
                        rl.removeClass('glow');
                        
                        switch(data) {
                            case "0":
                                res = "Oh nein! Ein Fehler!";
                                break;
                            case '1':
                                res = 'Dein gewählter Anzeigename enthält ungültige Zeichen, bitte wähle zwischen Buchstaben und Zahlen';
                                rl.removeAttr('style').addClass('failure');
                                break;
                            case '2':
                                res = 'Dein gewählter Anzeigename wird bereits verwendet';
                                rl.removeAttr('style').addClass('failure');
                                break;
                            case '3':
                                res = 'Dein gewählter Vorname enthält ungültige Zeichen';
                                rl.removeAttr('style').addClass('failure');
                                break;
                            case '4':
                                res = 'Dein gewählter Nachname enthält ungültige Zeichen';
                                rl.removeAttr('style').addClass('failure');
                                break;
                            default:
                                res = 'Erfolgreich gespeichert!';
                                rl.removeAttr('style').addClass('success');
                        }
                     
                        showDialer(res);
                        
                    }
                    
                });
            
            changeTimer = false;
        }, 750);
    }

});