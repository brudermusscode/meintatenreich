class Manage {

    static loadPage(url = false, order = false, react = false, loader = false) {

        if(url) {
    
            // if there's no order given for filtering,
            // just show the default page content
            if(!order) {
                order = "all";
            }

            // clear content before adding the filter
            react.empty();

            // show loader if true
            if(loader) {
                loader.show();
            }
    
            // start the ajax call for getting content
            $.ajax({
    
                url: url,
                data: { order: order },
                method: 'POST',
                type: "HTML",
                success: function(data){

                    // if we successfully got content from the other
                    // PHP file, ...
                    if(data !== 0) {

                        // ... hide the loader and ...
                        if(loader) {
                            loader.hide();
                        }

                        // prepend the data received
                        react.prepend(data);
                    } else {
                        showDialer("WrongoOo");
                    }
                },
                error: function(data){
                    console.error(data);
                }
            });
        }

    }

}

$(function() {

    let $doc, $bod, $body;

    $doc = $(document);
    $bod = $('body');
    $body = $('body');

    // filter index page by specific order ~ works
    $(document).on('click', '[data-action="manage:filter"] datalist ul li', function(){

        let $t, manage, react, loader, url, order;

        $t = $(this);
        manage = $t.closest('div[data-page]').data('page');
        react = $body.find('div[data-react="manage:filter"]');
        loader = $body.find('color-loader');
        order = $t.data('json')[0].order;

        switch(manage) {

            case "index":
                url = dynamicHost + '/_magic_/ajax/content/filter/index';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'orders':
                url = dynamicHost + '/_magic_/ajax/content/manage/filter/orders';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'products':
                url = dynamicHost + '/_magic_/ajax/content/manage/filter/products';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'customers':
                url = dynamicHost + '/_magic_/ajax/content/manage/filter/customers';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'overview':

                switch(order) {

                    case "orders":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/orders";
                        break;

                    case "customers":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/customers";
                        break;

                    case "ratings":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/ratings";
                        break;

                    case "#nofilter":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/all";
                        break;

                    default:
                        url = false;
                        break;
                }
                Manage.loadPage(url, order, react, loader);
                break;

            default:
                url = false;
                break;
        }
    })

    // manage: products
    // >> add images
    $(document).on("click", '[data-action="manage:products,add,addImage"]', function(){
        
        let $i = $(document).find('[data-form="uploadFiles:products,add"] input[type="file"]').click();
        
    })

    // >> edit images
    .on('click', '[data-action="manage:products,edit,addImage"]', function(){
        
        let $i = $(document).find('[data-react="manage:products,edit,addImage"]').click();
        
    })

    // manage: messages
    // >> check
    .on("click", '[data-action="overview:messages,check"]', function(){
        
        let $t = $(this);
        
        if(!$('main-content').hasClass('messages')) {
            
            showDialer('Öffne Nachrichten...');

            let ajax = $.ajax({
                url: '/_magic_/ajax/check',
                method: 'POST',
                type: 'TEXT',
                success: function(data) {

                    switch(data){
                        case '0':
                        case '':
                            showDialer('Ein Fehler ist aufgetreten...');
                            break;
                        case '1':
                            window.location.replace('/hk/admin/v1/secret/overview/messages');
                    }

                }
            });
            
        } else {
            showDialer('Du befindest dich im Nachrichten-Center!');
        }
        
    })

    // >> change tab and load content
    .on("click", '[data-action="overview:messages,panel"] .point', function(){
        
        let $t = $(this);
        let or = $t.data('order');
        let $c = '[data-load="overview:messages"]';
        
        if(!$bod.hasClass('loading')) {
            $($c).find('color-loader').show().nextAll().remove();
            $bod.addClass('loading');
            loadContent($c, '/hk/get/overview/messages', {order:or});
        }
        
    });
    
    if($('main-content').hasClass('messages')) {
        loadContent('[data-load="overview:messages"]', '/hk/get/overview/messages', {order:'got'});
    }

    $(document).on('click', '[data-action="overview:messages,open"]', function(){

        let $t = $(this);
        let $m = $t.closest('content-card');
        let $txt = $m.find('[data-react="overview:messages,open,fulltext"]');
        let mh = $m.height();

        if(!$m.hasClass('open')) {

            $t.find('i.material-icons').html('eject')
              .parents().eq(1).attr('data-tooltip', 'Schließen');
            $m.addClass('open');
            $txt.removeClass('trimfull');
            $txt.removeAttr('style');

        } else {

            $t.find('i.material-icons').html('launch')
              .parents().eq(1).attr('data-tooltip', 'Öffnen');
            $m.removeClass('open');
            $txt.css('height', '24px');
            $txt.addClass('trimfull');

        }

    }).on('click', '[data-action="overview:messages,read"]', function(){

        let $t = $(this);
        let $m = $t.closest('content-card').find('[data-react="overview:messages"]');

        if($m.hasClass('new')) {
            $m.addClass('read');
            $t.find('i.material-icons').html('unsubscribe')
              .parent().eq(1).attr('data-tooltip', 'Als ungelesen markieren');
            $m.find('input[name="isread"]').val('1');
            setTimeout(function(){
                $m.removeClass('read');
                $m.removeClass('new');
            }, 600);
        } else {
            $m.removeClass('read')
              .addClass('new');
            $t.find('i.material-icons').html('done')
              .parents().eq(1).attr('data-tooltip', 'Als gelesen markieren');
            $m.find('input[name="isread"]').val('0');
        }

        let $f = $m.find('[data-form="overview:messages,actions"]')
        let url = '/hk/ajax/overview/messages/actions';
        let id = $t.closest('content-card').data('json')[0].id;
        let dS  = $f.serialize() + '&id=' + id;

        showDialer('Speichern...');
        msgAction($f, url, dS);

    }).on('click', '[data-action="overview:messages,fav"]', function(){

        let $t = $(this);
        let $m = $t.closest('content-card').find('[data-react="overview:messages"]');

        if($m.hasClass('fav')) {
            $m.addClass('nofav');
            $t.find('i.material-icons').html('star_border')
              .parents().eq(1).attr('data-tooltip', 'Merken');
            $m.find('input[name="fav"]').val('0');
            setTimeout(function(){
                $m.removeClass('nofav');
                $m.removeClass('fav');
            }, 600);
        } else {
            $m.removeClass('nofav')
              .addClass('fav');
            $t.find('i.material-icons').html('star')
              .parents().eq(1).attr('data-tooltip', 'Nicht mehr merken');
            $m.find('input[name="fav"]').val('1');
        }

        let $f = $m.find('[data-form="overview:messages,actions"]')
        let url = '/hk/ajax/overview/messages/actions';
        let id = $t.closest('content-card').data('json')[0].id;
        let dS  = $f.serialize() + '&id=' + id;
        
        showDialer('Speichern...');
        msgAction($f, url, dS);

    });


    // please clean up thanks

    // * FUNCTIONS: Mailer, choose * //
    $(document).on('click', '[data-action="func:mailer,choose"] datalist ul li', function(){

        let $t = $(this);
        let id = $t.data('json')[0].mail;
        let url = '/hk/get/func/mailer';
        let dS = { id: id };
        let cl = $bod.find('color-loader');
        let co = $bod.find('[data-react="func:mailer,choose"]');

        co.empty();
        cl.show();

        let ajax = $.ajax({
            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){

                co.append(data);
                cl.hide();


            }
        });


    });   
    
    // * FUNCTIONS: Mailer, send * //
    $(document).on('click', '[data-action="func:mailer,send"]', function(){

        let $t = $(this);
        let wh = $t.data('wh');
        let form = $bod.find('[data-form="func:mailer"]');
        let url = '/hk/ajax/func/mailer';
        let dS = form.serialize();
        let res;

        let $c = $bod.find('[data-react="show:loader"]');
        
        addOverlay('248,187,208', $c, '%', false);
        let $cOv = $c.find('page-overlay');
        addLoader('color', $cOv);
        let $covLo = $cOv.find('color-loader');
        
        let ajax = $.ajax({
            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){

                console.log(data);
                
                if(data === 'success') {
                    res = 'Rundmail erfolgreich versandt!';
                    setTimeout(function(){
                        window.location.replace(window.location);
                    }, 1000);
                } else {
                    res = 'Ein Fehler ist aufgetreten...';
                    closeOverlay($cOv, false);
                }
                
                showDialer(res);

            }
        });


    });
    
    // * Selector: Overview, All * //
    $(document).on('click', '[data-action="selector:overview,all"] datalist ul li', function () {

        var $t = $(this);
        var dataStr = $t.data('json')[0].order;
        var dUrl;
        var $co = $('[data-react="get-content:overview,all"]');
        var $lo = $co.find('color-loader');

        switch (dataStr) {
            case 'orders':
                dUrl = '/hk/get/overview/all/orders';
                break;
            case 'customers':
                dUrl = '/hk/get/overview/all/customers';
                break;
            case '#nofilter':
                dUrl = '/hk/get/overview/all';
                break;
            case 'ratings':
                dUrl = '/hk/get/overview/all/ratings';
        }

        $lo.nextAll().remove();
        $lo.show();

        $.ajax({
            url: dUrl,
            type: 'HTML',
            METHOD: 'POST',
            success: function (data) {

                $lo.fadeOut(100);
                setTimeout(function () {
                    $co.append(data);
                }, 100);

            }
        });

    });
    
    // * Mail: Custom * //
    $(document).on('click', '[data-action="mail:custom"]', function(){
        
        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let rel = $t.data('json')[0].rel;
        let which = $t.data('json')[0].which;
        let url = '/hk/get/manage/customers/sendcustommail';
        let dS;
        let mail;
        
        if(which === 'customer'){
            dS = { rel: rel };
        } else {
            dS = { rel: rel };
        }
        
        let ajax = $.ajax({
            
            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                $lo.remove();
                $ov.append(data);
                
            }
            
        });
        
    })
    
    .on('click', '[data-action="mail:custom,send"]', function(){
        
        let $t = $(this);
        let res;
        let $wc = $('wide-container[data-json]');
        let $co = $t.closest('content-card');
        let id = $t.data('json')[0].id;
        let to = $t.data('json')[0].to;
        let url = '/hk/ajax/mail/custom';
        let val = $co.find('textarea').val();
        
        if($.trim(val).length < 1) {
            
            showDialer('Bitte gib eine Mitteilung ein...');
            
        } else {
        
            // ADD OVERLAY AND LOADER
            let $ov = $bod.find('page-overlay');
            addOverlay('255,255,255', $co, '%', false);
            let $wcOv = $co.find('page-overlay');
            addLoader('color', $wcOv);
            let $wcLo = $wcOv.find('color-loader');

            showDialer('Mitteilung wird gesendet...');

            let ajax = $.ajax({
                
                url: url,
                data: { id: id, text: val },
                method: 'POST',
                type: 'HTML',
                success: function(data,) {
                    
                    if(data === 'success') {
                        res = 'Mitteilung versandt an: <strong>' + to + '</strong>';
                        closeOverlay($ov, true);
                    } else {
                        res = 'Ein unbekannter Fehler ist aufgetreten';
                        closeOverlay($wcOv);
                    }
                    
                    showDialer(res);
                    
                }
                
            });
        
        }
        
    });
    
    // * Mail: Delivery Costs * //
    $(document).on('click', '[data-action="mail:deliverycosts"]', function(){

        let $t = $(this);
        let $r = $('[data-react="mail:deliverycosts"]');
        let th = $r.find('.input-outer').height();

        $r.css({ opacity:'1', height:'calc(' + th + 'px + 12px)', width:'100%', borderRadius:'20px' });

        $t.find('p').text('E-Mail versenden');
        $t.attr('data-action', 'mail:deliverycosts,send');

    })
    
    .on('click', '[data-action="mail:deliverycosts,send"]', function(){

        let $t = $(this);
        let res;
        let $wc = $('wide-container[data-json]');
        let $co = $t.closest('content-card');
        let url = '/hk/ajax/mail/dc';
        let id = $wc.data('json')[0].id;
        let co = $wc.find('[data-form="mail:deliverycosts"]').serialize();
        let dS = co + '&id=' + id;

        // RESET
        let $btn = $('[data-react="mail:deliverycosts"]');
        let $inp = $('[data-action="mail:deliverycosts,send"]');

        if($.trim($('[data-form="mail:deliverycosts"]').find('input').val()).length < 1) {
            
            showDialer('Bitte gib einen Preis ein...');
            
        } else {
        
            addOverlay('255,255,255', $co, '%', false);
            let $wcOv = $co.find('page-overlay');
            addLoader('color', $wcOv);
            let $wcLo = $wcOv.find('color-loader');

            showDialer('E-Mail wird gesendet...');

            // AJAX CALL
            let ajax = $.ajax({
                url: url,
                data: dS,
                type: 'TEXT',
                method: 'POST',
                success: function(data){

                    switch(data) {
                        case '0':
                        default:
                            res = 'Ein unbekannter Fehler ist aufgetreten';
                            break;
                        case '1':
                            res = 'Diese Bestellung existiert nicht'
                            break;
                        case 'success':
                            res = 'E-Mail wurde erfolgreich versandt';
                            $btn.remove();
                            $inp.remove();
                            
                            closeOverlay($wcOv);
                    }

                    showDialer(res);


                }
            });

        }

    });

});

let msgAction = function(form, ajaxUrl, data) {
    
    let $f = form;
    let url = ajaxUrl;
    let dS  = data;
    let res;
    
    let ajax = $.ajax({
        url: url,
        data: dS,
        method: 'POST',
        type: 'TEXT',
        success: function(data) {

            switch(data){
                case '':
                case '0':
                case '1':
                    res = 'Ein Fehler ist aufgetreten...';
                    break;
                case 'success':
                    res = 'Gespeichert!';
            }

            showDialer(res);

        }

    });

}