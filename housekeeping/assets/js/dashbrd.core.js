'use strict';

// create dynamic host
let parsedHostname, dynamicHost;

parsedHostname = psl.parse(location.hostname);
dynamicHost;

if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
    dynamicHost = "http://" + document.domain;
} else {
    dynamicHost = "https://www." + parsedHostname.domain;
}

$(function() {

    var $doc = $(document);
    var $bod = $('body');

    // no auto submission on enter
    $(document).on('submit', 'form', function(){
        return false;
    })

    // filter index page by specific order
    .on('click', '[data-action="manage:filter"] datalist ul li', function(){

        let $t = $(this);
        let or = $t.data('json')[0].order;
        let pg = $t.closest('div[data-page]').data('page');
        let react = $bod.find('div[data-react="manage:filter"]');
        let loader = $bod.find('color-loader');
        let url = false;
        let dS = { order: or };
    
        switch(pg) {
            case 'orders':
                url = dynamicHost + '/_magic_/ajax/content/manage/orders/filter/index';
                break;
            case 'products':
                url = dynamicHost + '/_magic_/ajax/content/manage/orders/filter/index';
                break;
            case 'customers':
                url = dynamicHost + '/_magic_/ajax/content/manage/orders/filter/index';
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
    
    })

    // basic visibility change of menu elements when being clicked,
    // main menu on the left side
    .on('click', '[data-structure="navigation"] .menu ul a li.point', function () {

        var $t = $(this);
        $t.parents().eq(3).find('.menu ul a li').removeClass('active');
        $t.addClass('active');

    })

    // open main menu on click when in mobile mode
    .on("click", '[data-action="open:menu,main"]', function(){

        console.log("ok");

        let $t, $m;

        $t = $(this),
        $m = $(document).find('[data-react="open:menu,main"]');

        if($m.hasClass('open')) {
            $m.removeClass('open');
            $t.removeClass('open');
        } else {
            $m.addClass('open');
            $t.addClass('open');
        }

    });

    // close datalists on mouseup, if current target is not that list
    $(window).on('mouseup', this, function (e) {

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
        
        let mainMenu = $(document).find('[data-react="open:menu,main"]');
        let mainMenuBtn = $(document).find('[data-action="open:menu,main"]');
        if (!($(e.target).closest(mainMenu).is(mainMenu) || $(e.target).closest(mainMenuBtn).is(mainMenuBtn))) {
            mainMenu.removeClass('open');
            mainMenuBtn.removeClass('open');
        }

    });

    // error responser keep showing on hover
    $('response-dialer').hover(function () {
        clearTimeout(dialerTimeout);
    }, function() {
        var rd = $('response-dialer');
        dialerTimeout = setTimeout(function(){ rd.removeAttr('style'); }, 3000);
    });

    // contantly check for messages, play sound if there are new ones
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
    
    // set the interval for message checking request
    setInterval(function(){
        requestMsg();
    }, 4000);

});

// load the content
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

let addOverlay = function(color, append, view = 'v', close = true) { 

    let col, app, clo, vee;

    col = color,
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
    
    let $ov;

    $ov = overlay;
    
    if(body === true) {
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

// fade images in
function fadeIn(obj) {
    let o = $(obj);
    o.addClass('tran-all').delay(10).removeClass('vishid opa0');
}

// check length of forms
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

let clearArray = function(array) {
    array = [];
}
