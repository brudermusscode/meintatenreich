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

    let $body = $('body');

    // no auto submission on enter
    $(document).on('submit', 'form', function(e){
        e.preventDefault();
        return false;
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

    })

    // close datalist when clicked on
    .on("click", "datalist li", function() {

        $(this).closest("datalist").removeAttr("style");

    });

    // close datalists on mouseup, if current target is not that list
    $(window).on('mouseup', this, function (e) {

        let datalist = $('[data-element="admin-select"]').find('datalist');

        // toggle manage filter
        if (!$(e.target).closest(datalist).is(datalist)) {

            datalist
            .removeAttr("style")
            .closest('[data-element="admin-select"]')
            .removeClass('open');
            
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

    // close overlay
    $(document).on("click", "[data-action='close-overlay']", function(){

        // find overlay and close it
        const closeOverlay = Overlay.close($body);

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
                        if(!$body.hasClass('has-new-msgs')) {
                            msgSound.play();
                        }
                        $('[data-action="overview:messages,check"]').find('.pulse').addClass('active');
                        $body.addClass('has-new-msgs');
                }
            }
        });
    }

    requestMsg();
    
    // set the interval for message checking request
    setInterval(function(){
        //requestMsg();
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
            
            $("body").removeClass('loading');

        }
    });

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
