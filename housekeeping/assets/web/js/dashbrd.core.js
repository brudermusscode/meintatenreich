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

        // close main menu in mobile mode
        if (!($(e.target).closest(mainMenu).is(mainMenu) || $(e.target).closest(mainMenuBtn).is(mainMenuBtn))) {
            mainMenu.removeClass('open');
            mainMenuBtn.removeClass('open');
        }

        // close content card overlay
        if (!($(e.target).closest("[data-element='overlay:content-card']").is("[data-element='overlay:content-card']"))) {
            closeContentCardOverlay(this.$overlay);
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

    // close content card overlay
    $(document).on("click", "[data-action='overlay:content-card,close']", function() {
        closeContentCardOverlay(this);
    });

    // TODO: contantly check for messages, play sound if there are new ones
    // ! here
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
function showDialer(text, icon = false, section = false) {

    let $dialer = $('response-dialer');

    // set icon to default if none was commited
    if(!icon) {
        icon = "notifications";
    }

    if(!section) {
        section = "Allgemein";
    }

    clearTimeout(dialerTimeout);

    $dialer.find(".lt .icon i").html(icon);
    $dialer.find(".lt .title").html(section);
    $dialer.find(".inr .text").html(text);

    $dialer.css('right', '24px');

    dialerTimeout = setTimeout(function(){ 
        $dialer.removeAttr('style'); 
    }, 6000);
}

// close responser on click
function closeDialer() {
    clearTimeout(dialerTimeout);
    var rd = $('response-dialer');
    dialerTimeout = rd.removeAttr('style');
}

// close content card overlay amk
let closeContentCardOverlay = function(cc) {
    $(cc).closest("[data-element='overlay:content-card']").removeClass("visible");
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
