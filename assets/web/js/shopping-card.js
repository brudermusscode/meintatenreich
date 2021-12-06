$(function(document) {

    let body = $("body");

    // delete
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
        let $shoppingCardAmount = $('[data-react="add-scard"] p');
        
        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'JSON',
            success: function(data) {

                if(data.status) {

                    $shoppingCardAmount.html(data.shoppingCardAmount - 1);

                    if(body.hasClass('calculated')) {
                        pricing(formData, appendPricing);
                    }
                    
                    t.parents().eq(1).css({ 'visibility':'hidden', 'opacity':'0' });

                    setTimeout(function(){

                        t.parents().eq(1).remove();
                    }, 400);
                }
                
                showDialer(data.message);
            },
            error: function(data) {
                console.error(data);
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
        
        let form, formData, prval, action, isValid, url, ov, clOv, lo;

        form = $('[data-form="scard"]');
        formData = form.serialize();
        prval = $.trim($('[data-name="products"]').val()).length;
        action = $(this).data('action');
        url = dynamicHost + "/ajax/functions/shopping-card/buy";
        isValid;
        
        form.find('input').each(function() {
            var element = $(this);
            if ($.trim(element.val()).length < 1) {
                isValid = false;
            }
        });
        
        formData = formData + '&action='+action;

        addOverlay(body, dark = true);
        ov = body.find('page-overlay');
        clOv = ov.find('close-overlay').remove();
        addLoader(ov, 'floating');
        lo = ov.find('loader').parent();
        
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
            type: 'JSON',
            success: function(response) {
                
                let price, delivery;

                if(response.status) {

                    price = response.price;
                    delivery = response.delivery;

                    // redirect to order success page
                    setTimeout(function(){
                        window.location.replace('?pr='+price+'&del='+delivery);
                    }, 600);
                } else {

                    // something went wrong, so close the overlay
                    removeOverlay(ov);
                }
                
                // show responsive dialer
                showDialer(response.message);
            },
            error: function(response) {
                console.error(response.responseText);
            }
        });
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
    
    let appendPricing, button, pricingHint, url, $body = $("body");

    appendPricing = append;
    button = $('[data-react="select-scard"]');
    pricingHint = $('[data-react="pricing-hint"]');

    url = dynamicHost + "/ajax/functions/shopping-card/pricing";

    $.ajax({

        url: url,
        data: data,
        method: 'POST',
        type: 'TEXT',
        success: function(response) {

            if(response.status) {

                // reassign url
                let url = dynamicHost + "/ajax/content/shopping-card/pricing";

                $.ajax({

                    url: url,
                    data: { price: response.price, delivery: response.delivery },
                    method: 'POST',
                    type: 'TEXT',
                    success: function(data) {

                        button.attr('data-action', 'buyshit');
                        button.removeAttr('disabled');
                        pricingHint.hide();
                        appendPricing.empty();
                        appendPricing.append(data);
                        $body.addClass('calculated');

                    }
                });
            } else {

                showDialer(response.message);
            }
        }
    });
}