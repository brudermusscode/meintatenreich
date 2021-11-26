$(function(){
    
    // VARS
    var $doc = $(document);
    var body = $('body');
    let pmeth = 'POST';
    let gmeth = 'GET';
    
    
    // ADDRESS
    // >> Add
    $(document).on('click', '[data-action="add-address"]', function(){
        
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        var action = 'add-address';
        
        $.ajax({
            
            data: { action: action },
            url: '/get/address',
            method: 'POST',
            type: 'HTML',
            success: function(data) {
            
                loader.remove();
                overlay.append(data);
            
            }
            
        });
        
    })
    .on('click', '[data-action="request-add-address"]', function(){
        
        var form = $('[data-form="address"]');
        var fullname = $.trim(form.find('input[name="fullname"]').val());
        var country = $.trim(form.find('input[name="country"]').val());
        var str = $.trim(form.find('input[name="str"]').val());
        var hnr = $.trim(form.find('input[name="hnr"]').val());
        var extra = $.trim(form.find('input[name="extra"]').val());
        var city = $.trim(form.find('input[name="city"]').val());
        var postcode = $.trim(form.find('input[name="postcode"]').val());
        var tel = $.trim(form.find('input[name="tel"]').val());
        
        if(fullname === '' || country === '' || str === '' || hnr === '' || city === '' || postcode === '' || tel === '') {
            showDialer('Bitte fülle alle erforderlichen Felder aus!');
        } else {
        
            var overlay = body.find('page-overlay');
            var wc = $('wide-container');
            addOverlay(wc, dark = true);
            var wcOverlay = wc.find('page-overlay');
            addLoader(wcOverlay, 'floating');
            wcOverlay.find('close-overlay').remove();

            var formData = $('[data-form="address"]').serialize();
            var res;
            var add = $('[data-react="add-content"]');

            $.ajax({

                data: formData,
                url: '/ajax/address',
                method: 'POST',
                type: 'TEXT',
                success: function(data, status, xhr) {
                    
                    var IS_JSON = false;
                    var ct = xhr.getResponseHeader("content-type") || "";
                    if(ct.indexOf('json') > -1) {
                        IS_JSON = true;
                    }
                    
                    switch(data) {
                        case '0':
                            res = 'Ein unbekannter Fehler ist aufgetreten!';
                            break;
                        case '1':
                            res = 'Ihre Straße ist fehlerhaft.';
                            break;
                        case '2':
                            res = 'Hausnummern können nur aus Buchstaben und Zahlen bestehen!';
                            break;
                        case '3':
                            res = 'Ihre Postleitzahl ist fehlerhaft!';
                            break;
                        case '4':
                            res = 'Ihre Stadt enthält ungültige Zeichen!';
                            break;
                        case '5':
                            res = 'Ihr Adresszusatz enthält ungültige Zeichen!';
                            break;
                        case '6':
                            res = 'Ihre Telefonnummer ist ungültig. Erlaubt sind Nummern von 0-9, "/" und "+"!';
                            break;
                        default:
                            if(IS_JSON === true) {
                                res = 'Ihre Adresse wurde erfolgreich hinzugefügt!';
                                var adid = xhr.responseJSON.adid;
                                formData = formData + '&adid=' + adid + '&address=' + str + '%20' + hnr;
                                wc.remove();
                                addTextDialogue(overlay, 'Erfolgreich hinzugefügt!');
                                setTimeout(function(){
                                    overlay.removeAttr('style');
                                    setTimeout(function(){
                                        overlay.remove();
                                    }, 400);
                                }, 1000);
                                
                                // Reload Overview
                                if(body.hasClass('scard')) {
                                    getScardOverview();
                                }
                                    
                                $.ajax({

                                    data: formData,
                                    url: 'elem/address',
                                    method: 'POST',
                                    type: 'HTML',
                                    success: function(data) {
                                        
                                        add.prepend(data);

                                    }

                                });
                            } else {
                                res = 'Ein unbekannter Fehler ist aufgetreten!';
                            }

                    }

                    showDialer(res);
                    wcOverlay.css('opacity', '0');
                    setTimeout(function(){
                        wcOverlay.remove();
                    }, 200);

                }

            });
            
        }
        
    })
    
    // >> Edit
    .on('click', '[data-action="edit-address"]', function(){
        
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        var action = 'edit-address';
        
        var t = $(this);
        var tdata = t.data('json');
        var adid = tdata[0].adid;
        
        $.ajax({
            
            data: { action: action, adid: adid },
            url: '/get/editaddress',
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                loader.remove();
                overlay.append(data);
                
            }
            
        });
        
    })
    .on('click', '[data-action="request-edit-address"]', function(){
        
        var form  = $('[data-form="edit-payment-method"]');
        var fullname = $.trim(form.find('input[name="fullname"]').val());
        var country = $.trim(form.find('input[name="country"]').val());
        var str = $.trim(form.find('input[name="str"]').val());
        var hnr = $.trim(form.find('input[name="hnr"]').val());
        var extra = $.trim(form.find('input[name="extra"]').val());
        var city = $.trim(form.find('input[name="city"]').val());
        var postcode = $.trim(form.find('input[name="postcode"]').val());
        var tel = $.trim(form.find('input[name="tel"]').val());
        var adid = $.trim(form.find('input[name="adid"]').val());
        
        var overlay = body.find('page-overlay');
        var wc = $('wide-container');
        addOverlay(wc, dark = true);
        var wcOverlay = wc.find('page-overlay');
        addLoader(wcOverlay, 'floating');
        wcOverlay.find('close-overlay').remove();

        var formData = $('[data-form="edit-address"]').serialize();
        var res;
        var addpm = $('[data-react="add-content"]');

        $.ajax({

            data: formData,
            url: '/ajax/editaddress',
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                switch(data) {
                    case '0':
                        res = 'Ein unbekannter Fehler ist aufgetreten!';
                        break;
                    case '1':
                        res = 'Ein Fehler ist aufgetreten. Dieser wurde wahrscheinlich durch Manipulation über die Developer-Konsole ausgelöst und wurde in unserem System vermerkt.';
                        break;
                    case '2':
                        res = 'Ihre Adresse ist ungültig!';
                        break;
                    case '3':
                        res = 'Ihre Postleitzahl ist ungültig!';
                        break;
                    case '4':
                        res = 'Ihr Stadt-Name enthält ungültige Zeichen!';
                        break;
                    case '5':
                        res = 'Ihr Adresszusatz enthält ungültige Zeichen!';
                        break;
                    case '6':
                        res = 'Ihre Telefonnummer ist ungültig!';
                        break;
                    case '7':
                        res = 'Ihre Name enthält ungültige Zeichen!';
                        break;
                    default:
                        res = 'Ihre Adresse wurde erfolgreich bearbeitet!';
                        wc.remove();
                        addTextDialogue(overlay, 'Erfolgreich gespeichert!');
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

            }

        });
        
    })
    
    // >> Delete
    .on('click', '[data-action="delete-address"]', function(){

        var t = $(this);
        var tdata = t.data('json');
        var id = tdata[0].id;
        var which = tdata[0].which;
        var action = 'delete';
        
        var wc = $('body').find('wide-container');
        var wcinr = wc.find('.zoom-in');
        addOverlay(wcinr, dark = true);
        var wcinrOverlay = wcinr.find('page-overlay');
        var closeOverlay = wcinrOverlay.find('close-overlay').remove();
        addLoader(wcinrOverlay, 'floating');
        var loader = wcinrOverlay.find('loader').parent();
        
        $.ajax({
            
            data: { action: action, id: id },
            url: '/elem/confirmdelete',
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                loader.remove();
                wcinrOverlay.prepend(data);
                var input = wcinrOverlay.find('input[name="which"]').val(which);
                
            }
            
        });

    });
    
    // DELETE REQUEST
    $(document).on('click', '[data-action="request-delete"]', function(){
        
        var t = $(this);
        var tdata = t.data('json');
        var id = tdata[0].id;
        var overlay = body.find('page-overlay');
        var wc = overlay.find('wide-container');
        var which = wc.find('input[name="which"]').val();
        
        var lel = body.find('wide-container page-overlay [data-structure="confirm-window"] .zoom-in');
        addOverlay(lel, dark = true);
        var lelOverlay = lel.find('page-overlay');
        lelOverlay.find('close-overlay').remove();
        addLoader(lelOverlay, 'floating');
        var lelOverlayLoader = lelOverlay.find('loader');
        
        var url;
        var dia;
        var res;
        
        if(which === 'address') {
            url = '/ajax/deleteaddress';
            dia = 'Adresse';
        } else if(which === 'payment-method') {
            url = '/ajax/deletepaymentmethod';
            dia = 'Bankverbindung';
        }
        
        $.ajax({
            
            data: { id: id },
            url: url,
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                var np = $('[data-react="add-content"]').find('#np-'+id);
                
                if(data === '1' || data === '0') {
                    res = 'Ein unbekannter Fehler ist aufgetreten!';
                } else {
                    res = dia+' erfolgreich entfernt!';
                    wc.remove();
                    np.fadeOut(800);
                    addTextDialogue(overlay, 'Erfolgreich gelöscht!');
                    setTimeout(function(){
                        overlay.removeAttr('style');
                        setTimeout(function(){
                            overlay.remove();
                        }, 400);
                    }, 1000);
                }
                
                showDialer(res);
                lelOverlay.removeAttr('style');
                setTimeout(function(){
                    lelOverlay.remove();
                }, 400);
                
            }
            
        });
        
    })
    .on('click', '[data-action="cancel-delete"]', function(){
        
        var wcinrOverlay = body.find('wide-container .zoom-in page-overlay').removeAttr('style');
        setTimeout(function(){
            wcinrOverlay.remove();
        }, 400);
        
    });

});