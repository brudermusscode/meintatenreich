$(function(document) {

    let body = $("body");

    // Delete
    $(document).on('click', '[data-action="delete-scard"]', function() {
        
        var t = $(this);
        var id = t.data('json')[0].id;
        var action = t.data('action');
        var res;
        var form = $('[data-form="scard"]');
        var formData = form.serialize();
        var action = $(this).data('action');
        var appendPricing = $('[data-react="pricing"]');
        formData = formData + '&action=pricing';
        let url = dynamicHost + "/ajax/functions/shopping-card/delete";
        
        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'TEXT',
            success: function(data) {

                switch(data) {
                    case '':
                    default:
                    case '0':
                        res = 'Ein unbekannter Fehler ist aufgetreten.';
                        break;
                    case '1':
                        res = 'Das von Ihnen gewählt Produkt existiert nicht!';
                        break;
                    case '2':
                        res = 'Produkt vom Warenkorb entfernt!';
                        
                        if(body.hasClass('calculated')) {
                            pricing(formData, appendPricing);
                        }
                        
                        t.parents().eq(1).css({ 'visibility':'hidden', 'opacity':'0' });
                        setTimeout(function(){
                            t.parents().eq(1).remove();
                        }, 400);
                }
                
                showDialer(res);
                
                
            }
            
        });
        
    })

    // Select payment method
    .on('click', '[data-action="accounts-scard"] .list ul li', function() {

        var t = $(this);
        var action = t.closest('[data-element="select"]').data('action');
        var accountReact = $('[data-react="account-scard"]');
        let url = dynamicHost + "/ajax/content/shopping-card/billings";

        $.ajax({

            url: url,
            data: { action: action },
            method: 'POST',
            type: 'TEXT',
            success: function(data) {

                switch(data) {
                    case "0":
                        console.log("someone likes to play");
                        break;
                    default:
                        accountReact.empty();
                        accountReact.removeClass('vishid opa0 hw1');
                        accountReact.append(data);
                }

            }

        });

    })

    // Buy
    .on('click', '[data-action="buyshit"]', function() {
        
        var form = $('[data-form="scard"]');
        var formData = form.serialize();
        var prval = $.trim($('[data-name="products"]').val()).length;
        var action = $(this).data('action');
        var isValid;
        var res;
        let url = dynamicHost + "/ajax/functions/shopping-card/buy";
        
        form.find('input').each(function() {
            var element = $(this);
            if ($.trim(element.val()).length < 1) {
                isValid = false;
            }
        });
        
        formData = formData + '&action='+action;
        
        if(isValid === false) {
            showDialer('Bitte wähle alle Optionen!');
        } else if(prval < 1) {
            showDialer('Dein Warenkorb ist leer!');
        } else {
            
            addOverlay(body, dark = true);
            var ov = body.find('page-overlay');
            var clOv = ov.find('close-overlay').remove();
            addLoader(ov, 'floating');
            var lo = ov.find('loader').parent();
            
            var removeOverlay = function(ov) {
                ov.removeAttr('style');
                setTimeout(function(){
                    ov.remove();
                }, 400);
            }
            
            $.ajax({
                
                url: url,
                data: formData,
                method: 'POST',
                type: 'TEXT',
                success: function(response) {
                    
                    console.log(response);

                    var resArray = ['', 0, 1, 2, 3, 4, 5, 6];
                    
                    if($.inArray(response, resArray) !== -1) {
                        removeOverlay(ov);
                    }
                    
                    switch(response) {
                        case '':
                        case 0:
                            res = 'Ein unbekannter Fehler ist aufgetreten!';
                            break;
                        case 1:
                            res = 'Dein Warenkorb ist leer';
                            break;
                        case 2:
                            res = 'Um eine Bestellung aufzugeben, muss dein Account verifiziert sein. Bitte schau in deinem E-Mail Postfach nach einer E-Mail unseres Shops';
                            break;
                        case 3:
                            res = 'Einige deiner Produkte sind nicht verfügbar';
                            break;
                        case 4:
                            res = 'Deine Zahlungsmethode ist ungültig';
                            break;
                        case 5:
                            res = 'Deine Lieferadresse ist ungültig';
                            break;
                        case 6:
                            res = 'Deine Liefermethode ist ungültig';
                            break;
                        default:
                            let price = response.price;
                            let delivery = response.delivery;
                            res = 'Bestellung erfolgreich, leite weiter...';
                            window.location.replace('?pr='+price+'&del='+delivery);
                    }
                    
                    showDialer(res);
                },
                error: function(response) {
                    console.log(response.responseText);
                }
            });
        }
    })

    .on('click', '[data-element="select"] .list ul li', function() {
        
        var form = $('[data-form="scard"]');
        var button = $('[data-react="select-scard"]');
        var prval = $.trim($('[data-name="products"]').val()).length;
        var isValid;
        var appendPricing = $('[data-react="pricing"]');
        var action = 'pricing';
        var res;
        
        setTimeout(function(){
            form.find('input').each(function() {
                var element = $(this);
                if ($.trim(element.val()).length < 1) {
                    isValid = false;
                }
            });
        
            var formData = form.serialize();
            formData = formData + '&action='+action;

            if(isValid === false || prval < 1) {
                button.attr('disabled', 'disabled');
                button.removeAttr('data-action');
                body.removeClass('calculated');
            } else {
                formData = formData + '&action='+action;
                showDialer('Preis wird berechnet...');
                pricing(formData, appendPricing);
            }
        }, 100);
        
    });

});


function getScardOverview() {

    let body = $('body');
    let append = $('[data-react="checkout"]');
    let action = 'scard-overview';
    let url = dynamicHost + "/ajax/content/shopping-card/overview";

    addOverlay(body);
    let ov = body.find('page-overlay');
    addLoader(ov, 'floating');
    let lo = ov.find('loader').parent();

    $.ajax({

        url: url,
        data: { action: action },
        method: 'POST',
        type: 'HTML',
        success: function(data) {

            switch(data) {
                case "0":
                    document.location.replace("/");
                    break;
                default:
                    append.empty();
                    append.append(data);
            }

            ov.removeAttr('style');
            setTimeout(function(){
                ov.remove();
            }, 400);

        }

    });

}

function pricing(data, append) {
    
    let body = $('body');
    let appendPricing = append;
    let button = $('[data-react="select-scard"]');
    let pricingHint = $('[data-react="pricing-hint"]');

    let url = dynamicHost + "/ajax/functions/shopping-card/pricing";
    let formData = data;
    let res;

    $.ajax({

        url: url,
        data: formData,
        method: 'POST',
        type: 'TEXT',
        success: function(response) {

            switch(response) {
                case '':
                case 0:
                    res = 'Ein unbekannter Fehler ist aufgetreten!';
                    break;
                case 1:
                    res = 'Dein Warenkorb ist leer!';
                    appendPricing.empty();
                    pricingHint.show();
                    button.attr('disabled', 'disabled');
                    button.removeAttr('data-action');
                    body.removeClass('calculated');
                    break;
                case 2:
                    res = 'Einige deiner Produkte sind nicht verfügbar!';
                    break;
                case 3:
                    res = 'Deine Zahlungsmethode ist ungültig!';
                    break;
                default:
                    let price = response.price;
                    let delivery = response.delAmt;

                    // reassign url
                    let url = dynamicHost + "/ajax/content/shopping-card/pricing";
                    res = 'Preis wird berechnet...';

                    $.ajax({

                        url: url,
                        data: { price: price, delivery: delivery },
                        method: 'POST',
                        type: 'TEXT',
                        success: function(data) {

                            button.attr('data-action', 'buyshit');
                            button.removeAttr('disabled');
                            pricingHint.hide();
                            appendPricing.empty();
                            appendPricing.append(data);
                            body.addClass('calculated');
                            showDialer('Preis berechnet!');

                        }

                    });

            }

            showDialer(res);

        }

    });
    
}