$(function() {

    let body = $("body");

    // >> get login, sign up
    $(document).on('click', '[data-action="open-login"], [data-action="open-signup"]', function(){

        var t = $(this);
        var tdata = t.data('json');
        tdata = tdata[0].open;
        var action;
        var url;
        if(tdata === 'login') {
            action = 'open-login';
            url = '/ajax/popups/sign/in';
        } else {
            action = 'open-signup';
            url = '/ajax/popups/sign/up';
        }
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();

        $.ajax({

            type: 'TEXT',
            method: 'POST',
            url: url,
            data: { action: action },
            success: function(data) {

                loader.remove();
                overlay.append(data);

            }

        });

    });

    // sign >> up
    $(document).on('click', '[data-action="signup"]', function(){

        let form, mail, lc, checkInputs, url, formData, overlay, lcOverlay;

        form = $('[data-form="signup"]');
        mail = form.find('input[name="mail"]').val();
        lc = $('login-container');
        url = dynamicHost + "/ajax/functions/sign/up";

        // add overlay
        addOverlay(lc, dark = true);
        overlay   = body.find('page-overlay');
        lcOverlay = lc.find('page-overlay');
        lcOverlay.find('close-overlay').remove();
        addLoader(lcOverlay, 'floating');
        
        // serialize form data
        formData = $('[data-form="signup"]').serialize();
        
        // start ajax xhr request
        $.ajax({
            
            data: formData,
            url: url,
            method: 'POST',
            type: 'JSON',
            success: function(data) {

                console.log(data);

                if(data.status) {

                    // remove overlay from login container
                    lc.remove();

                    // add text dialogue over the login container with
                    // success information (hopefully)
                    addTextDialogue(overlay, 'Erfolg!');

                    // set a timeout for reloading the page and get the user logged in
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000);

                } else {

                    // reset google recaptchaa
                    grecaptcha.reset();

                    // remove login container overlay after timeout                    
                    lcOverlay.css('opacity', '0');
                    setTimeout(function(){
                        lcOverlay.remove();
                    }, 200);
                }

                // responsive dialer popup, to inform users
                showDialer(data.message);
            },
            error: function(data) {
                console.error(data);
            }
        });
    })

    // sign >> in
    .on('submit', 'form[data-form="login"]', function(e){
        
        e.preventDefault();
        
        let $loginContainer, $loginContainerOverlay, formData, method, response, url, $overlay;

        formData = new FormData(this),
        method = $(this).attr("method"),
        url = dynamicHost + "/ajax/functions/sign/in",
        $loginContainer = $('login-container');

        if(formData.get("mail") == "" || formData.get("password") == "") {
            showDialer("Alle Felder m??ssen ausgef??llt sein");
            return false;
        }

        addOverlay($loginContainer, dark = true);
        $overlay = body.find('page-overlay');
        $loginContainerOverlay = body.find('page-overlay login-container page-overlay');
        $loginContainerOverlay.find('close-overlay').remove();
        addLoader($loginContainerOverlay, 'floating');

        $.ajax({
            
            data: formData,
            url: url,
            method: method,
            contentType: false,
            processData: false,
            type: 'JSON',
            success: function(data) {

                response = data.message;

                if(data.status) {
                    $loginContainer.remove();
                    addTextDialogue($overlay, 'Hallo<br>' + formData.get("mail"));
                    setTimeout(function(){
                        window.location.reload();
                    }, 1200);
                }

                showDialer(response);

                $loginContainerOverlay.css('opacity', '0');

                setTimeout(function(){
                    $loginContainerOverlay.remove();
                }, 200);
            },
            error: function(data) {
                console.error(data);
            }
        });
        
        return false;
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
            showDialer('Bitte f??lle alle Felder aus!');
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
                            res = 'Falls die angegebene E-Mail Adresse existiert, haben wir eine Mail zum Zur??cksetzen des Passworts gesendet'
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
            showDialer('Bitte f??lle alle Felder aus!');
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
                        addTextDialogue(ov, 'Passwort ge??ndert!');
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
                            res = 'Du hast dein Passwort ??ber diesen Key bereits ge??ndert';
                            break;
                        case '2':
                            res = 'Deine eingegebenen Passw??rter stimmen nicht ??berein';
                            break;
                        case '3':
                            res = 'Dein Passwort sollte sicherheitshalber min. 8 Zeichen enthalten';
                            break;
                        case '4':
                            res = 'Dein Passwort enth??lt unzul??ssige Zeichen. Erlaubt sind a-z, A-Z, 0-9, =.,_-+*#~?!&%$??!';
                            break;
                        default:
                            res = 'Erfolgreich ge??ndert!';
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