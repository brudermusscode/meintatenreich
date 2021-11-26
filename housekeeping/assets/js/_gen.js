'use strict';


// MANAGE OVERLAYS
let addOverlay = function(color, append, view = 'v', close = true) { 

    let col = color,
        app = append,
        clo = close,
        vee = view;
    
    if(vee === 'v') {
        $('body').addClass('ovhid');
        app.append('<page-overlay class="tran-all opa0 posfix" style="height:100vh;width:100vw;background:rgba(' + col + ',.92);"></page-overlay>');
    } else {
        app.append('<page-overlay class="tran-all opa0 posabs" style="height:100%;width:100%;background:rgba(' + col + ',.92);"></page-overlay>');
    }

    setTimeout(function(){
        let $ov = $('body').find('page-overlay');
        $ov.css({ opacity:'1' });
        if(close === true) {
            $ov.append('<close onclick="closeOverlay($(\'body\').find(\'page-overlay\'), true)"><div class="closer"><p>Klicke hier, um das Overlay zu schließen</p></div></close>');
        }
    }, 10);
    
}
let closeOverlay = function(overlay, body = false) {
    
    let $ov = overlay;
    let bod = body;
    
    if(bod === true) {
        $('body').removeClass('ovhid');
    }
    
    $ov.css('opacity', '0');
    setTimeout(function(){
        $ov.remove();
    }, 400);

}
let addLoader = function(type, append, floating = true) {
    let tp = type;
    let ap = append;
    let fl = floating;
    
    if(fl === false) {
        if(tp === 'color') {
            ap.append('<color-loader class="almid-h mt24 mb42"><inr><circl3 class="color-loader1"></circl3><circl3 class="color-loader2"></circl3></inr></color-loader>');
        }
    } else {
        if(tp === 'color') {
            ap.append('<color-loader class="almid"><inr><circl3 class="color-loader1"></circl3><circl3 class="color-loader2"></circl3></inr></color-loader>');
        }
    }
}

// MANAGE DIALER
var dialerTimeout;
function showDialer(text) {
    var t  = text;
    clearTimeout(dialerTimeout);
    var rd = $('response-dialer');
    rd.find('.inr p').html(text);
    rd.css('bottom', '12px');

    dialerTimeout = setTimeout(function(){ rd.removeAttr('style'); }, 3000);
}
function closeDialer() {
    clearTimeout(dialerTimeout);
    var rd = $('response-dialer');
    dialerTimeout = rd.removeAttr('style');
}

// IMAGE FADE IN
function fadeIn(obj) {
    let o = $(obj);
    o.addClass('tran-all').delay(10).removeClass('vishid opa0');
}

// VALIDATION
let checkForm = function(form) {
    
    let $f = form;
    
    $f.find('input, textarea').each(function() {
        let $e = $(this);
        if($.trim($e.val()).length < 1) {
            return false;
        }
    });
    
    return true;
    
}

    
// VARIOUS
    // MAKE BETTER WITH LOOP
    let clearArray = function(array) {
        let a = array;
        a = [];
    }


// START JQUERY FUNCTIONALITIES
$(function () {
    
    // VARS
    var $doc = $(document);
    var $bod = $('body');

    
    // VARIOUS
    $('response-dialer').hover(function () {
        clearTimeout(dialerTimeout);
    }, function() {
        var rd = $('response-dialer');
        dialerTimeout = setTimeout(function(){ rd.removeAttr('style'); }, 3000);
    });
    $doc.delegate('form', 'submit', function(){
        return false;
    });
    $doc.delegate('[data-action="open:menu,main"]', 'click', function(){

        let $t = $(this);
        let $m = $doc.find('[data-react="open:menu,main"]');

        if($m.hasClass('open')) {
            $m.removeClass('open');
            $t.removeClass('open');
        } else {
            $m.addClass('open');
            $t.addClass('open');
        }

    });
    $doc.delegate('[data-action="manage:products,add,addImage"]', 'click', function(){
        
        let $i = $doc.find('[data-react="manage:products,add,addImage"]').click();
        
    });
    $doc.delegate('[data-action="manage:products,edit,addImage"]', 'click', function(){
        
        let $i = $doc.find('[data-react="manage:products,edit,addImage"]').click();
        
    });
    $doc.delegate('[data-element="chooser"] ul li', 'click', function(){

        let $t = $(this);
        let $c = $t.closest('[data-element="chooser"]');

        $c.find('ul li').each(function(elem){
            let $e = $(this);
            $e.removeClass('active');
        });

        $t.addClass('active');

    });
    $doc.delegate('[data-action="overview:messages,check"]', 'click', function(){
        
        let $t = $(this);
        
        if(!$('main-content').hasClass('messages')) {
            
            showDialer('Öffne Nachrichten...');

            let ajax = $.ajax({
                url: '/hk/ajax/overview/messages/check',
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
        
    });
    $doc.on('click', '[data-action="manage:filter"] datalist ul li', function(){

    let $t = $(this);
    let or = $t.data('json')[0].order;
    let pg = $t.closest('div[data-page]').data('page');
    let react = $bod.find('div[data-react="manage:filter"]');
    let loader = $bod.find('color-loader');
    let url = false;
    let dS = { order: or };

    switch(pg) {
        case 'orders':
            url = '/hk/get/manage/filter/orders';
            break;
        case 'products':
            url = '/hk/get/manage/filter/products';
            break;
        case 'customers':
            url = '/hk/get/manage/filter/customers';
            break;
        default:
            url = false;
    }

    if(url !== false) {

        react.empty();
        loader.show();

        let ajax = $.ajax({
            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){

                loader.hide();
                react.prepend(data);

            },
            error: function(data){
                console.log(data);
            }
        });

    } else {
        showDialer('Ein fehler ist aufgetreten...');
    }

});
    
    // CHECK FOR MSGS
    let msgSound = new Audio('https://statics.meintatenreich.de/sounds/notify.mp3');
    let requestMsg = function() {
        $.ajax({
            url: '/hk/ajax/global/messages',
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                switch(data){
                    case '0':
                        return false;
                        break;
                    case '1':
                        if(!$bod.hasClass('has-new-msgs')) {
                            msgSound.play();
                        }
                        $('[data-action="overview:messages,check"]').find('.pulse').addClass('active');
                        $bod.addClass('has-new-msgs');
                }
            }
        });
    }
    requestMsg();
    
    setInterval(function(){
        requestMsg();
    }, 4000);
    
    // MENU BEAUTIFY
    $doc.on('click', '[data-structure="navigation"] .menu ul a li.point', function () {

        var $t = $(this);
        $t.parents().eq(3).find('.menu ul a li').removeClass('active');
        $t.addClass('active');

    });

    // BOOLEAN SELECTOR
    $doc.delegate('[data-element="boolean"] .bool', 'click', function(){

        let $t = $(this);

        $t.parent().children().removeClass('active');
        $t.addClass('active');

    });
    $doc.delegate('[data-element="boolean-great"]', 'click', function(){

        let $t = $(this);
        let $i = $t.find('input[type="hidden"]');

        if($t.hasClass('on')) {
            $i.val('0');
            $t.removeClass('on');
        } else {
            $i.val('1');
            $t.addClass('on');
        }

    });
    
    // TOOLTIP
    $doc.on('mouseenter', '[data-tooltip]', function () {

        var $t = $(this);
        var $text = $t.data('tooltip');
        var th = $t.height();
        var tw = $t.width();
        var al = $t.data('tooltip-align');

        if (!$t.hasClass('tooltip-active')) {
            $t.append('<tooltip class="dark tran-all-cubic"><tt-inr>' + $text + '</tt-inr></tooltip>');
            var $tt = $t.find('tooltip');
            if (al === 'bottom') {
                $tt.css({
                    'top': 'calc(' + th + 'px)'
                }).addClass('almid-h');
            } else if (al === 'left') {
                $tt.css({
                    'right': 'calc(' + tw + 'px)'
                }).addClass('almid-w');
            } else if (al === 'right') {
                $tt.css({
                    'left': 'calc(' + tw + 'px)'
                }).addClass('almid-w');
            } else {
                $tt.css({
                    'bottom': 'calc(' + th + 'px)'
                }).addClass('almid-h');
            }
            var show = setTimeout(function () {

                if (al === 'bottom') {
                    $tt.css({
                        'opacity': '1',
                        'top': 'calc(' + th + 'px + 6px)'
                    });
                } else if (al === 'left') {
                    $tt.css({
                        'opacity': '1',
                        'right': 'calc(' + tw + 'px + 6px)'
                    });
                } else if (al === 'right') {
                    $tt.css({
                        'opacity': '1',
                        'left': 'calc(' + tw + 'px + 6px)'
                    });
                } else {
                    $tt.css({
                        'opacity': '1',
                        'bottom': 'calc(' + th + 'px + 6px)'
                    });
                }

                $t.addClass('tooltip-active');
            }, 1);
        }

    }).on('mouseleave', '[data-tooltip]', function () {

        var $t = $(this);
        var $tt = $t.find('tooltip');

        if ($t.hasClass('tooltip-active')) {
            $tt.css('opacity', '0');

            var hide = setTimeout(function () {
                $tt.remove();
                $t.removeClass('tooltip-active');
            }, 100);

        }

    });

    // DATA LIST SELECTOR
    $doc.delegate('[data-element="admin-select"]', 'click', function () {

            var $t = $(this);
            var $dl = $t.find('datalist');
            var dlh = $dl.find('ul').height();
            var dlw = $dl.find('ul').width();
            var seh = $t.height();

            var lal = $t.data('list-align');
            var wid = $t.data('list-size');

            if (!$t.hasClass('open')) {
                $t.addClass('open');
                $dl.css({
                    'top': seh + 'px',
                    'opacity': '1',
                    'width': wid + 'px',
                    'height': 'calc(' + dlh + 'px + 24px)',
                    'border-radius': '4px'
                });
                if (lal === 'right') {
                    $dl.css({
                        right: '0'
                    });
                } else {
                    $dl.css({
                        left: '0'
                    });
                }

            }

        })
        .delegate('datalist ul li', 'click', function () {

            var $t = $(this);
            var $el = $t.closest('[data-element="admin-select"]');
            var $dl = $t.closest('datalist');
            var $ch = $el.find('.text');
            var ch = $t.html();

            if ($el.hasClass('open')) {
                $dl.css({
                    opacity:'',
                    top:'',
                    height:'',
                    width:'',
                    borderRadius:''
                });
                $ch.text(ch);
                setTimeout(function () {
                    $el.removeClass('open');
                }, 400);
            }
        
            let attr = $el.attr('data-input');
            if(typeof attr !== typeof undefined && attr !== false) {
                let id = $t.data('json')[0].id;
                let ip = $dl.find('input[type="hidden"]').val(id);
            }

        });

    // CLOSE DATALIST ON WINDOW MOUSEUP
    $(window).delegate(this, 'mouseup', function (e) {

        let selectList = $('[data-element="admin-select"]').find('datalist');
        if (!$(e.target).closest(selectList).is(selectList)) {
            selectList.css({
                opacity:'',
                top:'',
                height:'',
                width:'',
                borderRadius:''
            }).closest('[data-element="admin-select"]').removeClass('open');
            
        }
        
        let mainMenu = $doc.find('[data-react="open:menu,main"]');
        let mainMenuBtn = $doc.find('[data-action="open:menu,main"]');
        if (!($(e.target).closest(mainMenu).is(mainMenu) || $(e.target).closest(mainMenuBtn).is(mainMenuBtn))) {
            mainMenu.removeClass('open');
            mainMenuBtn.removeClass('open');
        }

    });


    // LOAD CONTENT
    let loadContent = function(content, url, data) {

        var $co = $(content);
        var $lo = $co.find('color-loader');
        var u = url;
        var d = data;
        
        $.ajax({
            url: u,
            data: d,
            type: 'HTML',
            method: 'POST',
            success: function (data) {

                $lo.fadeOut(100);
                setTimeout(function () {
                    $co.append(data);
                }, 100);
                
                $bod.removeClass('loading');

            }
        });

    }

    // Messages
    $doc.delegate('[data-action="overview:messages,panel"] .point', 'click', function(){
        
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
        
    // Overview
    } else if($('main-content').hasClass('overview')) {
        loadContent('[data-react="get-content:overview,all"]', '/hk/get/overview/all', {});
    }

    
    
    // ++++++++++++++++++++++++++++++++++ //
    // +++++++++++++ MANAGE +++++++++++++ //
    // ++++++++++++++++++++++++++++++++++ // 
    
    // * ORDER * //
    $doc.delegate('[data-action="manage:order"]', 'click', function(){
        
        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/orders/order';
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data, status) {
                
                $lo.remove();
                $ov.append(data);
                
            }
            
        });
        
    });
    
    // * ORDER: Change Status * //
    $doc.delegate('[data-action="manage:order,changestatus"] datalist ul li', 'click', function(){

        let $t = $(this);
        let res;
        let $wc = $('wide-container[data-json]');
        let $co = $t.closest('content-card');
        let url = '/hk/ajax/manage/orders/changestatus';
        let id = $wc.data('json')[0].id;
        let st = $t.data('json')[0].status;
        let dS = { id: id, status: st };
        let $btn = $t.closest('[data-action="manage:order,changestatus"]');

        // ADD OVERLAY AND LOADER
        addOverlay('255,255,255', $co, '%', false);
        let $wcOv = $co.find('page-overlay');
        addLoader('color', $wcOv);
        let $wcLo = $wcOv.find('color-loader');

        showDialer('Status wird geändert...');

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
                        res = 'Status wurde geändert und der Kunde wurde informiert';
                        $btn.removeClass('got done canceled sent');
                        switch(st) {
                            case 'got':
                                $btn.addClass('got');
                                $btn.find('.inr .ic:first-of-type i').html('new_releases');
                                $btn.find('.te').html('Neu');
                                break;
                            case 'done':
                                $btn.addClass('done');
                                $btn.find('.inr .ic:first-of-type i').html('done');
                                $btn.find('.te').html('Abgeschlossen');
                                break;
                            case 'sent':
                                $btn.addClass('sent');
                                $btn.find('.inr .ic:first-of-type i').html('watch_later');
                                $btn.find('.te').html('Unterwegs');
                                break;
                            case 'canceled':
                                $btn.addClass('canceled');
                                $btn.find('.inr .ic:first-of-type i').html('clear');
                                $btn.find('.te').html('Storniert');
                        }
                        
                        closeOverlay($wcOv);
                }

                showDialer(res);


            }
        });

    });
    
    // * ORDER: Paid * //
    $doc.delegate('[data-action="manage:order,paid"] datalist ul li', 'click', function(){

        let $t = $(this);
        let res;
        let $wc = $('wide-container[data-json]');
        let $co = $t.closest('content-card');
        let url = '/hk/ajax/manage/orders/paid';
        let id = $wc.data('json')[0].id;
        let st = $t.data('json')[0].status;
        let dS = { id: id, status: st };
        let $btn = $t.closest('[data-action="manage:order,paid"]');

        // ADD OVERLAY AND LOADER
        addOverlay('255,255,255', $co, '%', false);
        let $wcOv = $co.find('page-overlay');
        addLoader('color', $wcOv);
        let $wcLo = $wcOv.find('color-loader');

        showDialer('Status wird geändert...');

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
                        res = 'Status wurde geändert und der Kunde wurde informiert';
                        $btn.addClass('ok');
                        $btn.find('.inr .ic:first-of-type i').html('check_box');
                        $btn.find('.te').html('Bezahlt');
                        
                        closeOverlay($wcOv);
                }

                showDialer(res);


            }
        });

    });
    
    // * APP: Display * //
    $doc.delegate('[data-action="manage:app,display"]', 'click', function(){

        let $t = $(this);
        let $f = $bod.find('#data-form-manage-app-display');
        let dS = $f.serialize();

        showDialer('Speichere...');
        
        let ajax = $.ajax({

            url: '/hk/ajax/manage/app',
            data: dS,
            method: 'POST',
            type: 'TEXT',
            success: function(data){

                if(data === 'success') {
                    showDialer('Gespeichert!');
                } else {
                    showDialer('Ein Fehler ist aufgetreten...');
                }

            }

        });

    });
    
    // * PRODUCTS: Products, category, add * //
    $doc.delegate('[data-action="manage:products,category,add"]', 'click', function(){
        
        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/product/category/add';
        
        let ajax = $.ajax({
            
            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data, status) {
                
                $lo.remove();
                $ov.append(data);
                
            }
            
        });
        
    });
    
    // * PRODUCTS: Products, category, add, save * //
    $doc.delegate('[data-action="manage:products,category,add,save"]', 'click', function(){
        
        // HANDLE OVERLAY
        let $ov = $bod.find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/category/add';
        let $f = $('[data-form="manage:products,category,add"]');
        let $r = $('[data-react="manage:products,category,add"]');
        let dS = $f.serialize();
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                switch(data){
                    case '0':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        closeOverlay($ccOv, false);
                        break;
                    case '1':
                        res = 'Diese Kategorie existiert bereits...';
                        closeOverlay($ccOv, false);
                        break;
                    case 'success':
                        res = 'Hinzugefügt!';
                        $r.prepend('<content-card class="lt mr8 mb8"><div class="normal-box adjust"><div class="ph24 lh36"><p class="fw4" style="white-space:nowrap;">' + $f.find('input[name="name"]').val() + '</p></div></div></content-card>');
                        closeOverlay($ov, true);
                }
                
                showDialer(res);

            }

        });
        
    });
    
    // * PRODUCTS: Products, category, edit * //
    $doc.delegate('[data-action="manage:products,category,edit"]', 'click', function(){
        
        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/product/category/edit';
        let id = $t.data('json')[0].id;
        let dS = { id: id };
        let res;
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                $lo.remove();
                $ov.append(data);

            }

        });
        
    });
    
    // * PRODUCTS: Products, category, edit, delete * //
    $doc.delegate('[data-action="manage:products,category,edit,remove"]', 'click', function(){
        
        let $t = $(this);
        let $tp = $t.find('p');
        let co = $t.data('color');
        
        $tp.addClass('opa0');
        
        setTimeout(function(){
            $tp.css('color', 'white').text('Ganz sicher?').removeClass('opa0');
            $t.css('background', co);
            $t.attr('data-action', 'manage:products,category,edit,remove,confirm');
        }, 200);
        
    });
    
    // * PRODUCTS: Products, category, edit, confirm * //
    $doc.delegate('[data-action="manage:products,category,edit,confirm"]', 'click', function(){
        
        // HANDLE OVERLAY
        let $ov = $bod.find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/category/edit';
        let $f = $('[data-form="manage:products,category,edit"]');
        let dS = $f.serialize();
        
        showDialer('Speichere...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'TEXT',
            success: function(data){
                
                console.log(data);
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein Fehler ist aufgetreten...';
                        closeOverlay($ccOv, false);
                        break;
                    case 'success':
                        res = 'Gespeichert!';
                        closeOverlay($ccOv, false);
                }
                
                showDialer(res);

            }

        });
        
    });
    
    // * PRODUCTS: Products, category, edit, delete, confirm * //
    $doc.delegate('[data-action="manage:products,category,edit,remove,confirm"]', 'click', function(){
        
        // HANDLE OVERLAY
        let $ov = $bod.find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/category/edit/delete';
        let $f = $('[data-form="manage:products,category,edit"]');
        let dS = $f.serialize();
        
        showDialer('Lösche...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'TEXT',
            success: function(data){
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein Fehler ist aufgetreten...';
                        closeOverlay($ccOv, false);
                        break;
                    case 'success':
                        res = 'Gelöscht, bitte warten...';
                        window.location.replace(window.location);
                }
                
                showDialer(res);

            }

        });
        
    });
    
    // * PRODUCTS: Edit * //
    $doc.delegate('[data-action="manage:products,edit"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/product/edit';
        
        let ajax = $.ajax({

            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                $lo.remove();
                $ov.append(data);

            }

        });

    });
    
    // * PRODUCTS: Edit, addImage * //
    let uploadImagesArray = [];
    let uploadImagesErrorArray = [];
    
    $doc.delegate('[data-action="manage:products,edit,addImage"]', 'click', function(){

        let $t = $(this);
        let cas;
        let res;
        let addToArrayError;
        let $c = $t.closest('[data-react="manage:products,edit,addImage,show"]');
        let id = $c.closest('wide-container').data('json')[0].id;

        addOverlay('255,255,255', $c, '%', false);
        let $cOv = $c.find('page-overlay');
        addLoader('color', $cOv);
        let $covLo = $cOv.find('color-loader');
        
        let chosen = false;
        
        $('#image-penetration').fileupload({

            url: '/hk/ajax/manage/product/edit/uploadimage',
            dataType: 'json',
            formData: { id: id },
            autoUpload: false,
            add: function(e, data) {

                chosen = true;
                
                let fileTypeAllowed = /.\.(gif|jpg|jpeg|png)$/i;
                let fileName = data.originalFiles[0].name;

                if(!fileTypeAllowed.test(fileName)) {
                    showDialer('Die ausgewählten Bilder haben ein unzulässiges Format (JPG, JPEG, PNG, GIF).');
                } else {
                    data.submit();
                }

            },
            done: function(e, data) {
                
                let resTxt = data.jqXHR.responseJSON;
                let id = resTxt.id;
                let url = resTxt.url;
                
                switch(resTxt.status) {
                    case '':
                    case '0':
                    default:
                        cas = 0;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        break;
                    case '1':
                        cas = 1;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        
                        let ajax = $.ajax({
                            url: '/hk/get/elements/manage/products/addimage',
                            data: { id: id, url: url },
                            method: 'POST',
                            type: 'HTML',
                            success: function(data){
                                
                                $c.prepend(data);
                                console.log(uploadImagesArray, uploadImagesErrorArray);
                                
                            }
                        });
                        
                }
                
            },
            progressall: function(e, data) {

                let progress = parseInt(data.loaded / data.total * 100, 10);
                showDialer('Lade hoch...');

            },
            stop: function(data) {
                
                let arLen = uploadImagesErrorArray.length;
                
                if(uploadImagesErrorArray.indexOf(0) > -1) {
                    res = 'Nicht alle Bilder konnten hochgeladen werden...';
                    closeOverlay($cOv, false);
                } else {
                    res = 'Alle hinzugefügt!';
                    closeOverlay($cOv, false);
                }
                
                uploadImagesErrorArray = [];
                
                showDialer(res);
                
            }

        });
        
        if(chosen === false) {
            closeOverlay($cOv, false);
        }

    });

    // * PRODUCTS: Edit, save * //
    $doc.delegate('[data-action="manage:products,edit,save"]', 'click', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let id = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/product/edit';
        let dS = $('[data-form="manage:products,edit"]').serialize() + '&id=' + id;
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '2':
                        res = 'Die gewählte Kategorie existiert nicht...';
                        break;
                    case 'success':
                        res = 'Gespeichert!';
                        clearArray(uploadImagesArray);
                        clearArray(uploadImagesErrorArray);
                        console.log(uploadImagesArray, uploadImagesErrorArray);
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    });

    // * PRODUCTS: Add * //
    $doc.delegate('[data-action="manage:products,add"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/product/add';
        
        let ajax = $.ajax({

            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                $lo.remove();
                $ov.append(data);

            }

        });

    });
    
    // * PRODUCTS: Add, save * //
    $doc.delegate('[data-action="manage:products,add,save"]', 'click', function(){

        // HANDLE OVERLAY
        let $ov = $doc.find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/add/save';
        let $f = $ov.find('[data-form="manage:products,add"]');
        let dS = $f.serialize() + '&images=' + uploadImagesArray;
        
        if(checkForm($f) === false) {
            
            showDialer('Bitte fülle alle relevanten felder aus!');
            closeOverlay($ccOv, false);
            
        } else {
        
            let ajax = $.ajax({

                url: url,
                data: dS,
                method: 'POST',
                type: 'HTML',
                success: function(data){
                    
                    switch(data){
                        case '':
                            res = 'Bitte fülle alle Felder aus...'
                            closeOverlay($ccOv, false);
                            break;
                        case '0':
                        default:
                            res = 'Ein unbekannter Fehler ist aufgetreten';
                            closeOverlay($ccOv, false);
                            break;
                        case '1':
                            res = 'Ein Bild wurde nicht richtig hochgeladen. Bitte lade die Seite neu und versuche es erneut...';
                            closeOverlay($ccOv, false);
                            break;
                        case '2':
                            res = 'Die gewählte Produktkategorie existiert nicht...';
                            closeOverlay($ccOv, false);
                            break;
                        case '3':
                            res = 'Der Preis ist unzulässig...';
                            closeOverlay($ccOv, false);
                            break;
                        case '4':
                            res = 'Wähle ein Hauptbild aus...';
                            closeOverlay($ccOv, false);
                            break;
                        case 'success':
                            res = 'Produkt hinzugefügt!';
                            closeOverlay($ov, true);
                            clearArray(uploadImagesArray);
                            clearArray(uploadImagesErrorArray);
                            console.log(uploadImagesArray, uploadImagesErrorArray);
                            setTimeout(function(){
                                window.location.replace(window.location);
                            }, 2600);
                    }
                    
                    showDialer(res);

                }

            });
            
        }

    });
    
    // * PRODUCTS: Add, addImage * //
    $doc.delegate('#upload-new-images', 'click', function(e){
        
        let $t = $(this);
        let cas;
        let res;
        let addToArrayError;
        let $c = $t.closest('[data-react="manage:products,add,addImage,show"]');

        addOverlay('255,255,255', $c, '%', false);
        let $cOv = $c.find('page-overlay');
        addLoader('color', $cOv);
        let $covLo = $cOv.find('color-loader');
        
        let chosen = false;
        
        $('#upload-new-images').fileupload({

            url: '/hk/ajax/manage/product/add/uploadimage',
            dataType: 'json',
            autoUpload: false,
            add: function(e, data) {

                chosen = true;
                
                let fileTypeAllowed = /.\.(gif|jpg|jpeg|png)$/i;
                let fileName = data.originalFiles[0].name;

                if(!fileTypeAllowed.test(fileName)) {
                    showDialer('Die ausgewählten Bilder haben ein unzulässiges Format (JPG, JPEG, PNG, GIF).');
                } else {
                    data.submit();
                }

            },
            done: function(e, data) {

                let resTxt = data.jqXHR.responseJSON;
                let url = resTxt.url;
                let array = $('[data-react="manage:products,add,addImage,imgArray"][name]');

                switch(resTxt.status) {
                    case '0':
                    default:
                        cas = 0;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        break;
                    case '1':
                        cas = 1;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        let add = uploadImagesArray.push(url);
                        
                        let ajax = $.ajax({
                            url: '/hk/get/elements/manage/products/addimage',
                            data: { url: url },
                            method: 'POST',
                            type: 'HTML',
                            success: function(data){
                                
                                $c.prepend(data);
                                
                            }
                        });
                        
                }
                
            },
            progressall: function(e, data) {

                let progress = parseInt(data.loaded / data.total * 100, 10);
                showDialer('Lade hoch...');

            },
            stop: function(data) {
                
                let arLen = uploadImagesErrorArray.length;
                
                if(uploadImagesErrorArray.indexOf(0) > -1) {
                    res = 'Nicht alle Bilder konnten hochgeladen werden...';
                    closeOverlay($cOv, false);
                } else {
                    res = 'Alle hinzugefügt!';
                    closeOverlay($cOv, false);
                }
                
                $('[data-react="manage:products,add,addImage,gallery,info"]').css({
                    opacity:'1',
                    visibility:'visible',
                    bottom:'-24px'
                });
                
                uploadImagesErrorArray = [];
                
                showDialer(res);
                
            }

        });
        
//        if(chosen === false) {
//            closeOverlay($cOv, false);
//        }
        
    });
    
    // * PRODUCTS: Add, addImage, gallery * //
    $doc.delegate('[data-action="manage:products,add,addImage,gallery"] .item', 'click', function(){
        
        let $t = $(this);
        let url;
        let $r = $('[data-react="manage:products,add,addImage,gallery"]');
        let $c = $t.closest('.product-overview');
        let $h = $('[data-react="manage:products,add,addImage,gallery,info"]');
        
        if(!$t.hasClass('add-new')) {
            
            url = $t.data('json')[0].id;
        
            $c.find('.item').each(function() {
                let $e = $(this);
                $e.removeClass('gal');
            });

            $t.addClass('gal');
            $h.css({ opacity:'0', 'visibility':'hidden', bottom:'-42px' });
            $r.val(url);
            
        }
        
    });
    
    // * PRODUCTS: Edit, addImage, gallery * //
    $doc.delegate('[data-action="manage:products,edit,addImage,gallery"] .item', 'click', function(){
        
        let $t = $(this);
        let id;
        let $r = $('[data-react="manage:products,edit,addImage,gallery"]');
        let $c = $t.closest('.product-overview');
        
        if(!$t.hasClass('add-new')) {
            
            id = $t.data('json')[0].id;
        
            $c.find('.item').each(function() {
                let $e = $(this);
                $e.removeClass('gal');
            });

            $t.addClass('gal');
            $r.val(id);
            
        }
        
    });
    
    // * CUSTOMERS: Overview * //
    $doc.delegate('[data-action="manage:customers,overview"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/customers/overview';
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data, status) {
                
                $lo.remove();
                $ov.append(data);
                
            }
            
        });

    });
    
    // * CUSTOMERS: Orders * //
    $doc.delegate('[data-action="manage:customers,orders"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/customers/orders';
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                $lo.remove();
                $ov.append(data);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    });
    
    // * COURSES: Add * //
    $doc.delegate('[data-action="manage:course,add"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/course/add';
        
        let ajax = $.ajax({
            
            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                $lo.remove();
                $ov.append(data);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    });   
    
    // * COURSES: Add, save * //
    $doc.delegate('[data-action="manage:course,add,save"]', 'click', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/course/add';
        let dS = $('[data-form="manage:course,add"]').serialize();
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                console.log(data);
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '2':
                        res = 'Der Preis konnte nicht formatiert werden...';
                        break;
                    case '3':
                        res = 'Die Teilnehmerzahl muss nummerisch sein...';
                        break;
                    case 'success':
                        res = 'Hinzugefügt!';
                        setTimeout(function(){
                            window.location.replace(window.location);
                        }, 1000);
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    });
    
    // * COURSES: Edit * //
    $doc.delegate('[data-action="manage:course"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/course';
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                $lo.remove();
                $ov.append(data);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    });
    
    // * COURSES: Toggle * //
    $doc.delegate('[data-action="manage:course,toggle"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/ajax/manage/course/toggle';
        let res;
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data === 'on') {
                    $t.find('.ic i').html('blur_off');
                    $t.find('.ne').html('Deaktivieren');
                    $t.closest('.order').find('.course-status i').removeClass('cred').addClass('cgreen');
                    $t.closest('.order').find('.next-date').slideDown();
                    res = 'Kurs aktiviert!';
                } else if(data === 'off') {
                    $t.find('.ic i').html('blur_on');
                    $t.find('.ne').html('Aktivieren');
                    $t.closest('.order').find('.course-status i').removeClass('cgreen').addClass('cred');
                    $t.closest('.order').find('.next-date').slideUp();
                    res = 'Kurs deaktiviert!';
                } else {
                    res = 'Ein unbekannter Fehler ist aufgetreten...';
                }
                
                closeOverlay($ov, true);
                showDialer(res);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    }); 
    
    // * COURSES: Delete * //
    $doc.delegate('[data-action="manage:course,delete"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let $courseCont = $t.closest('content-card');
        let courseContH = $courseCont.height();
        let id = $t.data('json')[0].id;
        let url = '/hk/ajax/manage/course/delete';
        let res;
        
        showDialer('Lösche...');
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data === 'success') {
                    res = 'Kurs gelöscht!';
                    $courseCont.slideUp(300, 'swing');
                } else {
                    res = 'Ein unbekannter Fehler ist aufgetreten...';
                }
                
                closeOverlay($ov, true);
                showDialer(res);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    });
    
    // * COURSES: Dates, edit * //
    $doc.delegate('[data-action="manage:course,dates"]', 'click', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/course/dates';
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                $lo.remove();
                $ov.append(data);
                
            },
            error: function(data) {
                // SET ERROR REPORT
            }
            
        });

    });
    
    // * COURSES: Dates, edit, add * //
    $doc.delegate('[data-action="manage:course,dates,add"]', 'click', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let id = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/course/dates/add';
        let dS = $('[data-form="manage:course,edit"]').serialize() + '&id=' + id;
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                console.log(data);
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '2':
                        res = 'Das Datum hat ein falsches Format: Y-m-d...';
                        break;
                    case '3':
                        res = 'Die Zeit hat ein falsches Format: 00:00...';
                        break;
                    case 'success':
                        
                            let $c = $doc.find('[data-react="manage:courses,date,add"]');
                            let ajax2 = $.ajax({
                            url: '/hk/get/elements/manage/courses/date',
                            data: dS,
                            method: 'POST',
                            type: 'HTML',
                            success: function(data){
                                
                                $c.prepend(data);
                                
                                console.log(data);
                                
                            }
                        });
                        res = 'Hinzugefügt!';
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    });
    
    // * COURSES: Dates, edit, delete * //
    $doc.delegate('[data-action="manage:course,dates,delete"]', 'click', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let $courseCont = $t.closest('content-card');
        let courseContH = $courseCont.height();
        let id = $t.data('json')[0].id;
        let couid = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/course/dates/delete';
        let res;
        
        showDialer('Lösche...');
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id, couid: couid },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data === 'success') {
                    res = 'Termin gelöscht!';
                    $courseCont.slideUp(300, 'swing');
                } else {
                    res = 'Ein unbekannter Fehler ist aufgetreten...';
                }
                
                closeOverlay($ccOv, false);
                showDialer(res);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    });
    
    // * COURSES: Dates, edit, save * //
    $doc.delegate('[data-action="manage:course,edit,save"]', 'click', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let id = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/course/edit';
        let dS = $('[data-form="manage:course,edit"]').serialize() + '&id=' + id;
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '2':
                        res = 'Die gewählte Kategorie existiert nicht...';
                        break;
                    case 'success':
                        res = 'Gespeichert!';
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    });
    
    
    // * OVERVIEW: Messages, read & fav * //
    $doc.delegate('[data-action="overview:messages,open"]', 'click', function(){

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

    });
    let msgAction = function(form, ajaxUrl, data) {

        'use strict';
        
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
    $doc.delegate('[data-action="overview:messages,read"]', 'click', function(){

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

    })
        .delegate('[data-action="overview:messages,fav"]', 'click', function(){

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
    
    // * FUNCTIONS: Mailer, choose * //
    $doc.on('click', '[data-action="func:mailer,choose"] datalist ul li', function(){

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
    $doc.on('click', '[data-action="func:mailer,send"]', function(){

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
    
    // ++++++++++++++++++++++++++++++++++ //
    // ++++++++++++ SELECTOR ++++++++++++ //
    // ++++++++++++++++++++++++++++++++++ //
    
    // * Selector: Overview, All * //
    $doc.delegate('[data-action="selector:overview,all"] datalist ul li', 'click', function () {

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

    
    // ++++++++++++++++++++++++++++++++++ //
    // +++++++++++++ MAIL +++++++++++++++ //
    // ++++++++++++++++++++++++++++++++++ //
    
    // * Mail: Custom * //
    $doc.delegate('[data-action="mail:custom"]', 'click', function(){
        
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
        .delegate('[data-action="mail:custom,send"]', 'click', function(){
        
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
    $doc.delegate('[data-action="mail:deliverycosts"]', 'click', function(){

        let $t = $(this);
        let $r = $('[data-react="mail:deliverycosts"]');
        let th = $r.find('.input-outer').height();

        $r.css({ opacity:'1', height:'calc(' + th + 'px + 12px)', width:'100%', borderRadius:'20px' });

        $t.find('p').text('E-Mail versenden');
        $t.attr('data-action', 'mail:deliverycosts,send');

    })
        .delegate('[data-action="mail:deliverycosts,send"]', 'click', function(){

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
        
            // ADD OVERLAY AND LOADER
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
