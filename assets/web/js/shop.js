
$(function(){

    let body = $("body");


    // ############################
    // ++ startup 
    // ############################

    let loadProducts = $('[data-react="load-products"]');
    let loadProductsUrl;
    let loadProductsData;

    // show products on shop front
    if(body.hasClass('shop')) {
        loadProductsUrl = dynamicHost + '/ajax/content/shop/overview';
        loadProductsData = 'action=get-products&order=id';
        loadShop(loadProductsData, loadProductsUrl, loadProducts);

    // show search results on page: search
    } else if(body.hasClass('search')) {
        var q = body.find('#main').data('json')[0].q;
        loadProductsUrl = dynamicHost + '/ajax/content/shop/search';
        loadProductsData = 'action=get-products&order=id&q='+q;
        loadShop(loadProductsData, loadProductsUrl, loadProducts);
    }


    // ############################
    // ++ sort
    // ############################

    // sort by price
    $(document).on('click', '[data-action="sort-products"] .list ul li', function() {
        
        var t = $(this);
        var tdata = t.data('json');
        var order = tdata[0].order;
        var dataString;
        if(body.hasClass('shop')) {
            dataString = 'action=get-products&order='+order;
        } else {
            dataString = 'action=get-products&order='+order+'&q='+q;
        }
        
        let $inpkeep = $('[data-ability="keep"][data-input="products:sort,order"]');
        $inpkeep.val(order);
        
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        
        
        $.ajax({
            
            data: dataString,
            url: loadProductsUrl,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                loadProducts.empty();
                loadProducts.append(data);
                overlay.removeAttr('style');
                setTimeout(function(){
                    overlay.remove();
                }, 400);
                
            }
            
        });
        
    });

    // sort by categories
    $(document).on('click', '[data-action="products:sort,category"] .list ul li', function(){
        
        var t = $(this);
        var tdata = t.data('json');
        var id = tdata[0].id;
        var dataString;
        
        let $inpkeep = $('[data-ability="keep"][data-input="products:sort,order"]');
        let order = $inpkeep.val();
        
        if(body.hasClass('shop')) {
            dataString = 'action=get-category&order='+order+'&cat='+id;
        } else {
            dataString = 'action=get-category&order='+order+'&cat='+id+'&q='+q;
        }
        
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        
        
        $.ajax({
            
            data: dataString,
            url: loadProductsUrl,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                loadProducts.empty();
                loadProducts.append(data);
                overlay.removeAttr('style');
                setTimeout(function(){
                    overlay.remove();
                }, 400);
                
            }
            
        });
        
    })


    // ############################
    // ++ search 
    // ############################

    // search on enter
    .on('keyup', '[data-action="search"]', function(e) {
        if(e.keyCode == 13) {
            var val = $(this).val();
            window.location.replace('/search?q='+val);
        }
    })

    // start search on different ointeractions
    .on('input textInput paste', '[data-action="search"]', requestSearch);

    let waitRequestSearch = false;

    // search function (maybe pack into class?)
    function requestSearch() {
        
        // add loader on typing
        if(!(body.hasClass('loading')) && body.hasClass('shop')) {
            addOverlay(body);
            let overlay = body.find('page-overlay');
            let ovClose = overlay.find('close-overlay').remove();
            addLoader(overlay, 'floating');
            let loader = $('loader').parent();
            body.addClass('loading');
        }
        
        // wtf is this?
        if(waitRequestSearch !== false) clearTimeout(waitRequestSearch);

        waitRequestSearch = setTimeout(function(){
            loadProducts = $('[data-react="load-products"]');
            loadProductsUrl = dynamicHost + '/ajax/content/shop/overview';
            loadProductsData = 'action=get-products&order=id';

            let len = $.trim($('[data-action="search"]').val()).length;
            let val = $('[data-action="search"]').val();
            let append = $('[data-react="search"]');
            let action = 'search';
            let dataString;
            let l;
            let overlay = body.find('page-overlay');

            function searchAction(data, append) {
                append.addClass('active');
                append.empty();
                append.append(data);
            }

            function searchActionRemove(append, location) {
                if(location === 'shop') {
                    append.removeClass('active');
                    append.empty();
                    loadShop(loadProductsData, loadProductsUrl, loadProducts);
                } else {
                    append.removeClass('active');
                    append.empty();
                }
            }

            // if shop front, full page search
            if(body.hasClass('shop')) {
                l = 'shop';
                append = loadProducts;
                dataString = { action: action, q: val, l: l };

            // else show popup under searchbar
            } else {
                l = 'else';
                append = append;
                dataString = { action: action, q: val, l: l };
            }

            // check query length
            if(len > 0) {

                let url = dynamicHost + "/ajax/content/shop/search";

                $.ajax({

                    data: dataString,
                    url: url,
                    method: 'POST',
                    type: 'HTML',
                    success: function(data) {

                        searchAction(data, append);
                        overlay.removeAttr('style');
                        setTimeout(function(){
                            overlay.remove();
                            body.removeClass('loading');
                        }, 400);

                    }

                });

            // reset shop front
            } else {

                searchActionRemove(append, l);
                overlay.removeAttr('style');
                setTimeout(function(){
                    overlay.remove();
                    body.removeClass('loading');
                }, 400);
            }
            
            waitRequestSearch = false;
        }, 250);
    }


    // #############################
    // ++ billing methods
    // #############################

    // >> get popup window
    $(document).on('click', '[data-action="add-payment-method"]', function(){
        
        let url = dynamicHost + "/ajax/popups/add-billing";

        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        var action = 'add-payment-method';
        
        $.ajax({
            
            url:url,
            data: { action: action },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
            
                loader.remove();
                overlay.append(data);
            
            }
            
        });
        
    })

    // >> add
    .on('click', '[data-action="request-add-payment-method"]', function(){
        
        var form  = $('[data-form="payment-method"]');
        var acc   = $.trim(form.find('input[name="acc"]').val());
        var bic   = $.trim(form.find('input[name="bic"]').val());
        var iban  = $.trim(form.find('input[name="iban"]').val());
        
        if(acc === '' || bic === '' || iban === '') {
            showDialer('Bitte fülle alle Felder aus!');
        } else {

            var overlay = body.find('page-overlay');
            var wc = $('wide-container');
            addOverlay(wc, dark = true);
            var wcOverlay = wc.find('page-overlay');
            addLoader(wcOverlay, 'floating');
            wcOverlay.find('close-overlay').remove();
            
            let formData = $('[data-form="payment-method"]').serialize();
            let add = $('[data-react="add-content"]');
            let url = dynamicHost + "/ajax/functions/shop/add-billing";
            let res;
            let pmid;
            
            $.ajax({
                
                url: url,
                data: formData,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {
                    
                    // parse response
                    data = $.parseJSON(data);
                    pmid = data.pmid;
                    
                    switch(data) {
                        case '0':
                            res = 'Oh nein! Ein Fehler!';
                            break;
                        case '1':
                            res = 'Der Kontoinhaber enthält ungültige Zeichen';
                            break;
                        case '2':
                            res = 'Die BIC (Swift-Code) enthält ungültige Zeichen';
                            break;
                        case '3':
                            res = 'Die IBAN enthält ungültige Zeichen';
                            break;
                        case '4':
                            res = 'Die BIC (Swift-Code) kann nur aus 8-11 Zeichen bestehen';
                            break;
                        case '5':
                            res = 'Die IBAN muss mindestens aus 16 bis maximal aus 34 Zeichen bestehen';
                            break;
                        default:
                            res = 'Erfolgreich hinzugefügt!';

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

                            // reassign url
                            url = dynamicHost + "/ajax/content/elements/billing";
                            
                            // get billing element
                            $.ajax({

                                url: url,
                                data: { acc: acc, bic: bic, iban: iban, pmid: pmid },
                                method: 'POST',
                                type: 'HTML',
                                success: function(data) {

                                    add.prepend(data);
                                }

                            });
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
    
    // >> edit, open popup
    .on('click', '[data-action="edit-payment-method"]', function(){
        
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        var action = 'edit-payment-method';
        
        var t = $(this);
        var tdata = t.data('json');
        var pmid = tdata[0].pmid;
        
        $.ajax({
            
            data: { action: action, pmid: pmid },
            url: '/get/editpaymentmethod',
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                loader.remove();
                overlay.append(data);
                
            }
            
        });
        
    })
    
    // >> edit
    .on('click', '[data-action="request-edit-payment-method"]', function(){
        
        var form  = $('[data-form="edit-payment-method"]');
        var acc   = $.trim(form.find('input[name="acc"]').val());
        var bic   = $.trim(form.find('input[name="bic"]').val());
        var iban  = $.trim(form.find('input[name="iban"]').val());
        var pmid  = form.find('input[name="pmid"]').val();
        
        var overlay = body.find('page-overlay');
        var wc = $('wide-container');
        addOverlay(wc, dark = true);
        var wcOverlay = wc.find('page-overlay');
        addLoader(wcOverlay, 'floating');
        wcOverlay.find('close-overlay').remove();

        var formData = $('[data-form="edit-payment-method"]').serialize();
        var res;
        var addpm = $('[data-react="add-payment-method"]');

        $.ajax({

            data: formData,
            url: '/ajax/editpaymentmethod',
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
                        res = 'Ihre Eingaben enthalten ungültige Zeichen (Kontoinhaber)!';
                        break;
                    case '3':
                        res = 'Ihre Eingaben enthalten ungültige Zeichen (BIC/Swift-Code)!';
                        break;
                    case '4':
                        res = 'Ihre Eingaben enthalten ungültige Zeichen (IBAN)!';
                        break;
                    case '5':
                        res = 'Die BIC (Swift-Code) kann nur maximal aus 8-11 Zeichen bestehen!';
                        break;
                    case '6':
                        res = 'Die IBAN kann nur maximal aus 34 Zeichen bestehen!';
                        break;
                    default:
                        res = 'Ihre Bankverbindung wurde erfolgreich bearbeitet!';
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
    
    // >> delete
    .on('click', '[data-action="delete-payment-method"]', function(){

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

    })


    // #############################
    // ++ orders
    // #############################

    // >> cancel, confirmation popup
    .on('click', '[data-action="cancel-order"]', function() {
    
        var id = $(this).data('json')[0].id;
        var action = 'cancel-order';
        
        addOverlay(body, dark = true);
        var ov = body.find('page-overlay');
        addLoader(ov, 'floating');
        var lo = ov.find('loader').parent();
        
        $.ajax({
            
            data: { action: action, id: id },
            url: '/elem/confirmcancelorder',
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                lo.remove();
                ov.append(data);
                
            }
            
        });
        
    })

    // >> cancel
    .on('click', '[data-action="request-cancel-order"]', function() {
        
        var id = $(this).data('json')[0].id;
        var action = 'request-cancel-order';
        var res;
        
        var ov = body.find('page-overlay');
        var ovCl = ov.find('close-overlay').remove();
        var wc = ov.find('wide-container');
        addOverlay(wc, dark = true);
        var wcOv = wc.find('page-overlay');
        var wcOvCl = wcOv.find('close-overlay').remove();
        addLoader(wcOv, 'floating');
        var wcLo = wcOv.find('loader').parent();
        
        $.ajax({
            
            data: { action: action, id: id },
            url: '/ajax/requestcancelorder',
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                switch(data) {
                    case '':
                    case '0':
                        res = 'Ein unbekannter Fehler ist aufgetreten.';
                        break;
                    case '1':
                        res = 'Diese bestellung scheint nicht zu existieren!';
                        break;
                    case '2':
                        res = 'Die Stornierungsfrist von 2 Stunden ist bereits abgelaufen.';
                        closePageoverlay();
                        break;
                    default:
                        res = 'Stornierung erfolgreich, bitte warten...';
                        setTimeout(function(){
                            
                            window.location.replace(window.location);
                            
                        }, 1200);
                }
                
                showDialer(res);
                
            }
            
        });
        
    })
    
    // >> pay
    .on('click', '[data-action="manage:order,pay"]', function(){
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let dS = { id: id };
        let url = '/ajax/orders/pay';
        let res;
        
        showDialer('Bitte warten...');
        
        let ajax = $.ajax({
            url: url,
            data: dS,
            method: pmeth,
            type: 'JSON',
            success: function(data){
                
                switch(data){
                    case '0':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '1':
                        res = 'Diese Bestellung scheint nicht zu existieren...';
                        break;
                    case 'success':
                        res = 'Als bezahlt markiert!';
                        $t.removeAttr('data-action data-json').attr('disabled', 'disabled').html('Als bezahlt markiert').toggleClass('hlf-white-s hlf-blue-s');
                }
                
                showDialer(res);
                
            }
            
        });
        
    });

});

// load shop front with products
function loadShop(data, url, append) {
    
    addLoader(append);
    
    $.ajax({

        data: data,
        url: url,
        method: 'POST',
        type: 'HTML',
        success: function(data) {

            var loader = append.find('loader').remove();
            var res;
            if(data === '0') {
                res = 'Oh nein! Ein Fehler!';
                showDialer(res);
            } else {
                append.append(data);
            }

        }

    });
}