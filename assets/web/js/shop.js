
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
        loadProductsUrl = dynamicHost + '/ajax/content/shop/search-redirect';
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
            
                switch(data) {
                    case "0":
                        console.log("Someone likes to play");
                        break;
                    default:
                        loader.remove();
                        overlay.append(data);
                }
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
            let res, pmid, sepa;
            
            $.ajax({
                
                url: url,
                data: formData,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {

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

                            // parse response
                            data = $.parseJSON(data);
                            pmid = data.pmid;
                            sepa = data.sepa;

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
                                data: { 
                                    acc: acc, 
                                    bic: bic, 
                                    iban: iban, 
                                    pmid: pmid, 
                                    sepa: sepa 
                                },
                                method: 'POST',
                                type: 'HTML',
                                success: function(data) {

                                    switch(data) {
                                        case "0":
                                            res = "Something went wrong";
                                            break;
                                        default:
                                            add.prepend(data);
                                    }
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
        let url = dynamicHost + "/ajax/popups/edit-billing";
        
        $.ajax({
            
            url: url,
            data: { action: action, pmid: pmid },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                switch(data) {
                    case "0":
                    case "1":
                        res = "Something went wrong";
                        break;
                    default:
                        loader.remove();
                        overlay.append(data);
                }
            }
        });
    })
    
    // >> edit
    .on('click', '[data-action="request-edit-payment-method"]', function(){
        
        let overlay = body.find('page-overlay');
        let wc = $('wide-container');
        addOverlay(wc, dark = true);
        let wcOverlay = wc.find('page-overlay');
        addLoader(wcOverlay, 'floating');
        wcOverlay.find('close-overlay').remove();

        let pmid = $('[data-form="edit-payment-method"]').serializeArray();
        let formData = $('[data-form="edit-payment-method"]').serialize();
        let noticePaper = $('#np-' + pmid[0].value);
        let changeName = noticePaper.find("[react-edit-account]");
        let url = dynamicHost + "/ajax/functions/shop/edit-billing";
        let res;

        $.ajax({

            url: url,
            data: formData,
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                switch(data) {
                    case '0':
                    case '1':
                        res = 'Oh nein! Ein Fehler!';
                        break;
                    case '2':
                        res = 'Der eingegebene Kontoinhaber enthält ungültige Zeichen';
                        break;
                    default:
                        res = 'Die Bankverbindung wurde erfolgreich bearbeitet';

                        wc.remove();

                        addTextDialogue(overlay, 'Erfolgreich gespeichert!');

                        changeName.html(data);

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

        let t = $(this);

        let tdata = t.data('json');
        let id = tdata[0].id;
        let which = tdata[0].which;
        let action = 'delete';
        
        let wc = $('body').find('wide-container');
        let wcinr = wc.find('.zoom-in');
        addOverlay(wcinr, dark = true);
        let wcinrOverlay = wcinr.find('page-overlay');
        let closeOverlay = wcinrOverlay.find('close-overlay').remove();
        addLoader(wcinrOverlay, 'floating');
        let loader = wcinrOverlay.find('loader').parent();
        
        let url = dynamicHost + "/ajax/content/elements/delete-confirmation";

        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                switch(data) {
                    case "0":
                        console.log("Someone likes to play");
                        break;
                    default:
                        loader.remove();
                        wcinrOverlay.prepend(data);
                        let input = wcinrOverlay.find('input[name="which"]').val(which);
                }
            }

        });
    })


    // #############################
    // ++ addresses
    // #############################

    // >> add popoup
    .on('click', '[data-action="add-address"]', function(){
        
        addOverlay(body);
        let overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        let loader = $('loader').parent();

        let action = 'add-address';
        let url = dynamicHost + "/ajax/popups/add-address";
        
        $.ajax({

            url: url,
            data: { action: action },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
            
                switch(data) {
                    case "0":
                        showDialer("Bruder");
                        break;
                    default:
                        loader.remove();
                        overlay.append(data);
            
                }
            }
        });
    })

    // >> add
    .on('click', '[data-action="request-add-address"]', function(){

        let formArray = $('[data-form="address"]').serializeArray();

        if(
            formArray[0].value == '' || 
            formArray[1].value == '' || 
            formArray[2].value == '' || 
            formArray[3].value == '' || 
            formArray[5].value == '' || 
            formArray[6].value == '' || 
            formArray[7].value == ''
        ) {

            showDialer('Bitte fülle alle erforderlichen Felder aus');
        } else {
        
            let overlay = body.find('page-overlay');
            let wc = $('wide-container');
            addOverlay(wc, dark = true);
            let wcOverlay = wc.find('page-overlay');
            addLoader(wcOverlay, 'floating');
            wcOverlay.find('close-overlay').remove();

            let formData = $('[data-form="address"]').serialize();
            let add = $('[data-react="add-content"]');
            let res;
            let url = dynamicHost + "/ajax/functions/shop/add-address";

            $.ajax({

                url: url,
                data: formData,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {

                    switch(data) {
                        case '0':
                            res = 'Oh nein! Ein Fehler!';
                            break;
                        case '1':
                            res = 'Die eingegebene Straße enthält ungültige Zeichen';
                            break;
                        case '2':
                            res = 'Die eingegebene Hausnummer enthält ungültige Zeichen';
                            break;
                        case '3':
                            res = 'Die eingegebene Postleitzahl enthält ungültige Zeichen';
                            break;
                        case '4':
                            res = 'Die eingegebene Stadt enthält ungültige Zeichen';
                            break;
                        case '5':
                            res = 'Der eingegebene Adresszusatz enthält ungültige Zeichen';
                            break;
                        case '6':
                            res = 'Die eingegebene Telefonnummer enthält ungültige Zeichen. Erlaubt sind 0-9, "/", "+"';
                            break;
                        default:

                            let adid, street, housenumber;
                            adid = data.adid;
                            street = data.street;
                            housenumber = data.housenumber;

                            res = 'Ihre Adresse wurde erfolgreich hinzugefügt!';
                            formData = formData + '&adid=' + adid + '&address=' + street + '%20' + housenumber;
                            url = dynamicHost + "/ajax/content/elements/address";

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

                                url: url,
                                data: formData,
                                method: 'POST',
                                type: 'HTML',
                                success: function(data) {
                                    
                                    switch(data) {
                                        case "0":
                                            res = "Something went wrong";
                                            break;
                                        default:
                                            add.prepend(data);
                                    }
                                }
                            });
                    }

                    showDialer(res);
                    wcOverlay.css('opacity', '0');
                    setTimeout(function(){
                        wcOverlay.remove();
                    }, 200);

                },
                error: function(data) {
                    
                    console.log(data.responseText);
                }

            });
            
        }
        
    })
    
    // >> edit: open popup
    .on('click', '[data-action="edit-address"]', function(){
        
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();
        var action = 'edit-address';
        
        var t = $(this);
        var tdata = t.data('json');
        var adid = tdata[0].adid;

        console.error(adid, tdata);

        let url = dynamicHost + "/ajax/popups/edit-address";
        
        $.ajax({
            
            url: url,
            data: { action: action, adid: adid },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                console.log(data);

                switch (data) {
                    case "0":
                    case "1":
                        console.log("Someone likes to play");
                        break;
                    default:
                        loader.remove();
                        overlay.append(data);
                }
                
            }
            
        });
        
    })

    // >> edit: request
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
    
    // >> delete
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

        let url = dynamicHost + "/ajax/content/elements/delete-confirmation";
        
        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                loader.remove();
                wcinrOverlay.prepend(data);
                var input = wcinrOverlay.find('input[name="which"]').val(which);
                
            }
            
        });

    })
    
    // ------------------------------

    // deletion confirmation popup
    .on('click', '[data-action="request-delete"]', function(){
        
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
            url = '/ajax/functions/shop/delete-address';
            dia = 'Adresse';
        } else if(which === 'billing') {
            url = '/ajax/functions/shop/delete-billing';
            dia = 'Bankverbindung';
        }
        
        $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'TEXT',
            success: function(data) {

                var np = $('[data-react="add-content"]').find('#np-'+id);
                
                switch(data) {
                    case "0":
                        res = 'Oh nein! Ein Fehler!';
                        break;
                    default:
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
                
            },
            error: function(data) {
                // add error output
            }
            
        });
        
    })

    // close deletion confirmation popup
    .on('click', '[data-action="cancel-delete"]', function(){
        
        var wcinrOverlay = body.find('wide-container .zoom-in page-overlay').removeAttr('style');
        setTimeout(function(){
            wcinrOverlay.remove();
        }, 400);
        
    })


    // #############################
    // ++ orders
    // #############################

    // >> cancel confirmation popup
    .on('click', '[data-action="cancel-order"]', function() {
    
        
        addOverlay(body, dark = true);
        let ov = body.find('page-overlay');
        addLoader(ov, 'floating');
        let lo = ov.find('loader').parent();
        
        let id = $(this).data('json')[0].id;
        let action = 'cancel-order';
        let url = dynamicHost + "/ajax/content/elements/cancelorder-confirmation";

        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                switch(data) {
                    case "0":
                        console.log("someone likes to play");
                        break;
                    default:
                        lo.remove();
                        ov.append(data);
                }
            },
            error: function(data) {
                console.error(data.responseText);
            }
        });
    })

    // >> cancel
    .on('click', '[data-action="request-cancel-order"]', function() {
        
        var ov = body.find('page-overlay');
        var ovCl = ov.find('close-overlay').remove();
        var wc = ov.find('wide-container');
        addOverlay(wc, dark = true);
        var wcOv = wc.find('page-overlay');
        var wcOvCl = wcOv.find('close-overlay').remove();
        addLoader(wcOv, 'floating');
        var wcLo = wcOv.find('loader').parent();
        
        let id = $(this).data('json')[0].id;
        let action = 'request-cancel-order';
        let res;
        let url = dynamicHost + "/ajax/functions/orders/cancel";

        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'TEXT',
            success: function(data) {

                switch(data) {
                    case '0':
                    case '1':
                        res = 'Oh nein! Ein Fehler!';
                        break;
                    case '2':
                        res = 'Die Stornierungsfrist von 2 Stunden ist bereits abgelaufen';
                        closePageoverlay();
                        break;
                    default:
                        res = 'Stornierung erfolgreich, bitte warten...';
                        setTimeout(function(){
                            
                            window.location.replace(window.location);
                            
                        }, 1200);
                }
                
                showDialer(res);
                
            },
            error: function(data) {
                // add error log
            }
            
        });
        
    })
    
    // >> pay
    .on('click', '[data-action="manage:order,pay"]', function(){
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let dS = { id: id };
        let url = dynamicHost + '/ajax/functions/orders/pay';
        let res;
        
        showDialer('Bitte warten...');
        
        let ajax = $.ajax({
            url: url,
            data: dS,
            method: "POST",
            type: 'JSON',
            success: function(data){
                
                switch(data){
                    case '0':
                    case '1':
                        res = 'Oh nein! Ein Fehler!';
                        break;
                    default:
                        res = 'Als bezahlt markiert';
                        $t.removeAttr('data-action data-json').attr('disabled', 'disabled').html('Als bezahlt markiert').toggleClass('hlf-white-s hlf-blue-s');
                }
                
                showDialer(res);
                
            }
            
        });
        
    })

    // >> confirm received
    .on('click', '[data-action="order:received,confirm"]', function() {

        let $t = $(this);
        let id = $t.data('json')[0].id;
        let dS = {
            id: id
        };
        let url = dynamicHost + '/ajax/functions/orders/confirm-received';
        let res;

        $.ajax({

            url: url,
            data: dS,
            type: 'TEXT',
            method: 'POST',
            success: function(data) {

                console.log($.parseJSON(data));

                switch(data) {
                    case "0":
                    case "1":
                        console.log("someone likes to play");
                        break;
                    default:
                        res = 'Vielen Dank für die Bestätigung!';
                        $t.attr('disabled', 'disabled');
                        $t.html('Ware erhalten bestätigt');
                }

                showDialer(res);
            }
        });
    });
});

// load shop front with products
function loadShop(data, url, append) {
    
    let loader, res;

    addLoader(append);
    
    $.ajax({

        data: data,
        url: url,
        method: 'POST',
        type: 'HTML',
        success: function(data) {

            console.log(data);

            loader = append.find('loader').remove(),
            res;

            if(data === '0') {
                res = 'Oh nein! Ein Fehler!';
                showDialer(res);
            } else {
                append.append(data);
            }

        }

    });
}