'use strict';

// create dynamic host
let parsedHostname = psl.parse(location.hostname);
let dynamicHost;
if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
    dynamicHost = "http://" + document.domain;
} else {
    dynamicHost = "https://www." + parsedHostname.domain;
}

$(function(){

    let body = $("body");

    // select fields
    $(document).on('click', '[data-element="select"] .select', function(){

        var t = $(this);
        var list = t.parent().find('.list');
        var listh = list.find('ul').height();

        list.css({ 'height':listh+'px', 'opacity':'1', 'visibility':'visible' });

    })

    .on('click', '[data-element="select"] .list ul li', function(){

        var t = $(this),
            list = t.parents('.list'),
            text = t.html(),
            chan = t.parents().eq(2).find('.select p:first-of-type'),
            chani = t.parents().eq(2).find('.select p:first-of-type i'),
            dataSel = t.parents().eq(1).attr('data-select'),
            newtext,
            iban,
            data,
            input,
            address,
            city;
        
        
        if(dataSel === 'accounts') {
            
            newtext = t.find('.list-title .name p').html();
            iban = t.find('.list-title .iban').text();
            iban = getNumbers(iban);
            newtext = newtext+' &nbsp; <span style="color:#999;font-size:.8em;">IBAN &bull;&bull;&bull;&bull;'+iban;
            data = t.data('json')[0].id;
            input = t.parents().eq(2).find('input[name="account"]').val(data);
            chan.html('<i class="icon-user"></i> &nbsp;&nbsp; '+newtext+'</span>');
            
        } else if(dataSel === 'addresses') {
            
            address = t.find('.list-title .address').text();
            city = t.find('.list-title .city').text();
            data = t.data('json')[0].id;
            newtext = t.find('.list-title .name p').html();
            newtext = '<i class="icon-home"></i> &nbsp;&nbsp; '+newtext+' &nbsp; <span style="color:#999;font-size:.8em;">'+address+', '+city+'</span>';
            input = t.parents().eq(2).find('input[name="address"]').val(data);
            chan.html(newtext);
            
        } else if(dataSel === 'delivery') {
           
            data = t.data('json')[0].type;
            newtext = t.find('.list-title .name p').html();
            city = t.find('.list-title .under p').text();
            newtext = '<i class="icon-truck"></i> &nbsp;&nbsp; '+newtext+' &nbsp; <span style="color:#999;font-size:.8em;">'+city+'</span>';
            input = t.parents().eq(2).find('input[name="delivery"]').val(data);
            chan.html(newtext);
            
        } else {
            
            data = t.data('json')[0].id;
            chan.html(text);
            input = t.parents().eq(2).find('input[name]').val(data);
            
        }
        
        list.removeAttr('style');

    });
    
    $(window).on('mouseup', function(e){

        var selectList = $('[data-element="select"] .list');

        if (!$(e.target).closest(selectList).is(selectList)) {
            selectList.removeAttr('style');
        }

    });

    // response error behavior on hover
    $('response-dialer').hover(function () {
        clearTimeout(dialerTimeout);
    }, function() {
        var rd = $('response-dialer');
        dialerTimeout = setTimeout(function(){ rd.removeAttr('style'); }, 3000);
    });

    // close response error
    $(document).on('click', '[data-action="close-dialer"]', function(){
        closeDialer();
    });

    // get back to shop? really necessary?
    $(document).on('click', '[data-action="shop"]', function(){
        window.location.replace('/');
    });

    // close response overlay
    $(document).on('click', '[data-action="close-overlay"]', function(){
        closePageoverlay();
    });
    
});

function setCookie(key, value) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000 * (10 * 365)));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}

function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
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

function addTextDialogue(elem, text) {
    elem.prepend('<text-dialogue class="almid fade-in-opacity"><p>'+text+'</p></text-dialogue>');
}

function addOverlay(elem, dark = false) {
    if(dark === true) {
        elem.prepend('<page-overlay class="tran-all" style="background:rgba(0,0,0,.84);"><close-overlay data-action="close-overlay"></close-overlay></page-overlay>');
    } else {
        elem.prepend('<page-overlay class="tran-all"><close-overlay data-action="close-overlay"></close-overlay></page-overlay>');
    }
    var overlay = $('page-overlay');
    setTimeout(function(){
        overlay.css('opacity', '1');
    }, 1);
}

function addLoader(elem, position) {
    var p = position,
        lo;
    if(p === 'floating') {
        lo = elem.find('loader').parent();
        elem.append('<div class="almid bgf rdcir p24 posabs mshd-1"><loader></loader></div>');
    } else {
        lo = elem.find('loader');
        elem.append('<loader class="almid-h"></loader>');
    }
    
    setTimeout(function(){
        lo.css('opacity', '1');
    }, 1);
}

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

function closePageoverlay() {
    var overlay = $('page-overlay').css({ 'visibility':'hidden', 'opacity':'0' });
    setTimeout(function(){
        overlay.remove();
    }, 400);
}

function fadeIn(obj) {
    $(obj).fadeIn(250);
}

function fadeInVisOpa(obj) {
    $(obj).css({ 'visibility':'visible', 'opacity':'1' });
}

function fadeImages(obj, parent = false) {
    if(parent) {
        obj = $(obj).parent();
    } else {
        obj = $(obj);
    }

    return obj.css({ 'visibility':'visible', 'opacity':'1' });
}

function fadeInVisOpaBg(obj) {
    obj.css({ 'visibility':'visible', 'opacity':'1' });
}

function getNumbers(inputString){
    var regex=/\d+\.\d+|\.\d+|\d+/g, 
        results = [],
        n;

    while(n = regex.exec(inputString)) {
        results.push(parseFloat(n[0]));
    }

    return results;
}

let checkFormInputEmpty = function(form) {
    
    'use strict';
    
    form.find('input').each(function() {
        let $t = $(this);
        if($.trim($t.val()).length < 1) {
            return false;
        }
    });
    return true;
}