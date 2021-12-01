$(function() {

    let body = $("body");

    // sign >> up
    $(document).on('click', '[data-action="signup"]', function(){

        let form = $('[data-form="signup"]');
        let mail = form.find('input[name="mail"]').val();
        let lc = $('login-container');
        let checkInputs = checkFormInputEmpty(form);
        let url = dynamicHost + "/ajax/functions/sign/up";
        
        if(checkInputs === false) {
            showDialer('Bitte fülle alle Felder aus!');
        } else {

            if(!validateEmail(mail)) {
                showDialer('Ihre E-Mail hat ein falsches Format. Bitte nutze name@host.endung');
            } else {

                addOverlay(lc, dark = true);
                let overlay   = body.find('page-overlay');
                let lcOverlay = lc.find('page-overlay');
                lcOverlay.find('close-overlay').remove();
                addLoader(lcOverlay, 'floating');
                
                let formData = $('[data-form="signup"]').serialize();
                
                $.ajax({
                    
                    data: formData,
                    url: url,
                    method: 'POST',
                    type: 'TEXT',
                    success: function(data){
                        
                        grecaptcha.reset();
                        let resp;

                        switch(data) {
                            case '0':
                                resp = 'Ein unbekannter Fehler ist aufgetreten.';
                                break;
                            case '1':
                                resp = 'Bitte akzeptiere die <a href="/intern/index#dsg-general-cookies" target="_blank">Cookie-Bedingungen</a>, um fortzufahren';
                                break;
                            case '2':
                                resp = 'Bitte akzeptiere unsere <a href="/intern/index" target="_blank">AGB</a> und <a href="#" target="_blank">Datenschutzerklärung</a>!';
                                break;
                            case '3':
                                resp = 'Die gewählten Passwörter stimmen nicht überein';
                                break;
                            case '4':
                                resp = 'Bitte wähle ein Passwort zwischen 8 und 32 Zeichen';
                                break;
                            case '5':
                                resp = 'Der Captcha-Code scheint falsch zu sein, versuche es erneut';
                                break;
                            case '6':
                                resp = 'Deine E-Mail hat ein falsches Format. Bitte nutze name@host.endung!';
                                break;
                            case '7':
                                resp = 'Diese E-Mail Adresse wird bereits verwendet';
                                break;
                            default:

                                console.log($.parseJSON(data));
                                resp = 'Du hast Dich erfolgreich registriert. Eine E-Mail zur Bestätigung wurde an <span style="color:#F1D394;"><strong>'+mail+'</strong></span> gesendet!';
                                lc.remove();
                                addTextDialogue(overlay, 'Erfolg!');

                                setTimeout(function(){
                                    window.location.reload();
                                }, 2000);
                        }
                        
                        showDialer(resp);
                        lcOverlay.css('opacity', '0');
                        setTimeout(function(){
                            lcOverlay.remove();
                        }, 200);
                        
                    }
                    
                });

            }

        }

    })

    // sign >> in
    .on('click', '[data-action="signin"]', function(){
        
        var mail = $('input[name="mail"]').val();
        var pass = $('input[name="password"]').val();
        var lc    = $('login-container');
        let url = dynamicHost + "/ajax/functions/sign/in";
        
        if(mail === '' || pass === '') {

            showDialer('Bitte fülle alle Felder aus!');

        } else {

            addOverlay(lc, dark = true);
            var overlay   = body.find('page-overlay');
            var lcOverlay = body.find('page-overlay login-container page-overlay');
            lcOverlay.find('close-overlay').remove();
            addLoader(lcOverlay, 'floating');
            
            var formData = $('[data-form="login"]').serialize();
            
            $.ajax({
                
                data: formData,
                url: url,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {
                    
                    let resp;
                    
                    console.log(data);

                    switch(data) {
                        case '1':
                            resp = 'Ein unbekannter Fehler ist aufgetreten und wurde umgehend gemeldet.';
                            break;
                        case '2':
                            resp = 'Bitte akzeptieren Sie das Setzen von <a href="#">Cookies</a> unsererseits!';
                            break;
                        case '3':
                            resp = 'Ihr Nutzername/E-Mail oder Passwort ist falsch!';
                            break;
                        case '4':
                            resp = 'Ihr Nutzername/E-Mail oder Passwort ist falsch!';
                            break;
                        default:
                            resp = 'Erfolgreich eingeloggt!';
                            lc.remove();
                            addTextDialogue(overlay, 'Hallo<br>'+mail);
                            setTimeout(function(){
                                window.location.reload();
                            }, 1200);
                            
                    }

                    showDialer(resp);
                    lcOverlay.css('opacity', '0');
                    setTimeout(function(){
                        lcOverlay.remove();
                    }, 200);
                    
                }
                
            });
            
        }
        
    })

    // >> forgot password open popup
    .on('click', '[data-action="forgot-password"]', function(){
        
        let ov = body.find('page-overlay');
        let lc = ov.find('login-container');
        let ac = 'forgot-password';
        
        lc.fadeOut(100);
        addLoader(ov, 'floating');
        var lo = ov.find('loader').parent();
        
        let url = dynamicHost + "/ajax/popups/forgot-password";

        setTimeout(function(){
            
            $.ajax({

                url: url,
                data: { action: ac },
                method: 'POST',
                type: 'HTML',
                success: function(data) {

                    switch(data) {
                        case "0":
                        case "1":
                            console.log("someone likes to play");
                            break;
                        default:
                            lo.remove();
                            ov.append(data);
                            form = $('[data-form="fgp"]').find('input[name="mail"]').focus();
                    }
                }
            });
        }, 100);
    })

    // >> forgot password send mail
    .on('click', '[data-action="new-password"]', function() {
        
        var form = $('[data-form="fgp"]');
        var val  = $.trim(form.find('input[name="mail"]').val());
        var ac = 'forgot-password';
        var res;
        
        var dataString = form.serialize() + '&action=' + ac;
        
        if(val < 1) {
            showDialer('Bitte fülle alle Felder aus!');
        } else {
            
            form.find('input[name="mail"]').blur();
            
            var ov = body.find('page-overlay');
            var wc = ov.find('wide-container');
            addOverlay(wc, dark = true);
            var wcClose = wc.find('close-overlay').remove();
            var wcOv = wc.find('page-overlay');
            addLoader(wcOv, 'floating');
            
            let url = dynamicHost + "/ajax/functions/sign/forgot-password";

            $.ajax({

                url: url,
                data: dataString,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {

                    console.log(data);

                    wcOv.removeAttr('style');
                    setTimeout(function(){
                        wcOv.remove();
                    }, 400);
                    
                    switch(data) {
                        case '':
                        case '0':
                        case '2':
                            res = 'Oh nein! Ein Fehler!';
                            break;
                        case '1':
                            res = 'Deine E-Mail hat ein falsches Format. Bitte Nutze name@host.endung';
                            break;
                        default:
                            res = 'Falls die angegebene E-Mail Adresse existiert, haben wir eine Mail zum Zurücksetzen des Passworts gesendet'
                            ov.removeAttr('style');
                            setTimeout(function(){
                                ov.remove();
                            }, 400);
                            
                    }
                    
                    showDialer(res);

                }

            });
            
        }
        
    })

    // >> forgot password close popup
    .on('submit', '[data-form="fgp"]', function(e) {
        
        var btn = $(this).parents().eq(3).find('button');
        
        btn.click();
        return false;
        
    })

    // forgot password request
    .on('click', '[data-action="request-new-password"]', function() {

        var form = $('[data-form="new-password"]');
        var inputsEmpty = checkFormInputEmpty(form);
        var action = 'forgot-password-2';
        var res;

        if(inputsEmpty === false) {
            showDialer('Bitte fülle alle Felder aus!');
        } else {

            var formData = form.serialize() + '&action=' + action;

            addOverlay(body);
            var ov = body.find('page-overlay');
            var ovCl = ov.find('close-overlay').remove();
            addLoader(ov, 'floating');
            var lo = ov.find('loader').parent();
            var dataArray = ['', '0', '1', '5', '2', '3', '4'];

            let url = dynamicHost + "/ajax/functions/sign/forgot-password";

            $.ajax({

                url: url,
                data: formData,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {

                    console.log($.parseJSON(data));

                    lo.remove();

                    if($.inArray(data, dataArray)) {
                        ov.removeAttr('style');
                        setTimeout(function(){
                            ov.remove();
                        }, 400);
                    } else {
                        addTextDialogue(ov, 'Passwort geändert!');
                        setTimeout(function(){
                            window.location.replace("/");
                        }, 1200);
                    }

                    switch(data) {
                        case '':
                        case '0':
                        case '5':
                            res = 'Ein unbekannter Fehler ist aufgetreten.';
                            break;
                        case '1':
                            res = 'Du hast dein Passwort über diesen Key bereits geändert';
                            break;
                        case '2':
                            res = 'Deine eingegebenen Passwörter stimmen nicht überein';
                            break;
                        case '3':
                            res = 'Dein Passwort sollte sicherheitshalber min. 8 Zeichen enthalten';
                            break;
                        case '4':
                            res = 'Dein Passwort enthält unzulässige Zeichen. Erlaubt sind a-z, A-Z, 0-9, =.,_-+*#~?!&%$§!';
                            break;
                        default:
                            res = 'Erfolgreich geändert!';
                            setTimeout(function(){
                                window.location.replace("/");
                            }, 600);

                    }

                    showDialer(res);

                }

            });

        }

    })

    // sign >> out
    .on('click', '[data-action="signout"]', function(){
        
        let action = 'logout';
        let url = dynamicHost + "/ajax/functions/sign/out";
        let res;
        
        addOverlay(body);
        let ov = body.find('page-overlay');
        addLoader(ov, 'floating');
        let lo = ov.find('loader').parent();
        
        $.ajax({
            
            url: url,
            data: { action: action },
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                lo.remove();
                
                if(data === '0') {
                    res = 'Erfolgreich ausgeloggt!';
                    addTextDialogue(ov, 'Ausgeloggt');
                    setTimeout(function(){
                        window.location.replace("/");
                    }, 600);
                } else {
                    res = 'Ein unbekannter Fehler ist aufgetreten!';
                    ov.removeAttr('style');
                    setTimeout(function(){
                        ov.remove();
                    }, 400);
                }
                
                showDialer(res);
                
            }
            
        });
    });

});