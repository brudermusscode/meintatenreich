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

    // change password popup
    $(document).on('click', '[data-action="open-change-password"]', function(){
        
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        var action = 'open-change-password';
        let url = dynamicHost + "/ajax/popups/change-password";
        
        $.ajax({
            
            url: url,
            data: { action: action },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
            
                loader.remove();
                overlay.append(data);
            
            }
            
        });
        
    })

    // change password
    .on('click', '[data-action="request-password-change"]', function(){
        
        var pwo = $.trim($('[data-form="change-password"] input[name="oldpass"]').val());
        var pw1 = $.trim($('[data-form="change-password"] input[name="newpass"]').val());
        var pw2 = $.trim($('[data-form="change-password"] input[name="newpass2"]').val());
        
        if(pwo === '' || pw1 === '' || pw2 === '') {
            showDialer('Bitte fülle alle Felder aus!');
        } else if(!pw1 == pw2) {
            showDialer('Ihre neuen Passwörter stimmen nicht überein!');
        } else {
            
            var overlay = body.find('page-overlay');
            var wc = $('wide-container');
            addOverlay(wc, dark = true);
            var wcOverlay = wc.find('page-overlay');
            addLoader(wcOverlay, 'floating');
            wcOverlay.find('close-overlay').remove();
            
            var formData = $('[data-form="change-password"]').serialize();
            var res;
            let url = dynamicHost + "/ajax/functions/user/change-password";
            
            $.ajax({
                
                url: url,
                data: formData,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {

                    switch(data) {
                        case '0':
                            res = 'Ein unbekannter Fehler ist aufgetreten';
                            break;
                        case '1':
                            res = 'Das derzeitig verwendete Passwort ist falsch';
                            break;
                        case '2':
                            res = 'Die Passwordbestätigung war ungültig';
                            break;
                        case '3':
                            res = 'Bitte verwende nicht dein altes Passwort';
                            break;
                        case '4':
                            res = 'Das Passwort sollte mindestens aus 8 Zeichen bestehen';
                            break;
                        case '5':
                            res = 'Ihr Passwort enthält ungültige Zeichen!';
                            var pwcreact = $('[data-react="change-password"]');
                            pwcreact.html('Gültige Zeichen sind: a-z, A-Z, 0-9, =.,_-+*#~?!&%$§');
                            break;
                        default:
                            res = 'Ihr Passwort wurde erfolgreich geändert!';
                            wc.remove();
                            addTextDialogue(overlay, 'Erfolgreich geändert!');
                            setTimeout(function(){
                                overlay.removeAttr('style');
                                setTimeout(function(){
                                    overlay.remove();
                                }, 400);
                            }, 1000);
                    }
                    
                    showDialer(res);
                    wcOverlay.css('opacity', '0');
                    setTimeout(function(){
                        wcOverlay.remove();
                    }, 200);
                    
                },
                error: function(data) {
                    // nothing so far
                }
                
            });
            
        }
        
    })

});