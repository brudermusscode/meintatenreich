$(function() {

    let loader, $body = $("body");

    loader = $body.find("color-loader");

    $(document)

    // >> check & open message center ~ works
    .on("click", '[data-action="overview:messages,check"]', function(){
        
        let url, $t = $(this);
        
        url = dynamicHost + "/_magic_/ajax/functions/mail/check";

        if(!$('main-content').hasClass('messages')) {
            
            showDialer('Öffne Nachrichten...');

            $.ajax({
                url: url,
                method: 'POST',
                type: 'JSON',
                success: function(data) {

                    if(data.status) {

                        setTimeout(function(){
                            // redirect to message center
                            window.location.replace(dynamicHost + '/_coffee_corner_//messages');
                        }, 600);
                    } else {

                        // show error message
                        showDialer(data.message);
                    }
                },
                error: function(data) {
                    console.error(data);
                }
            });
            
        } else {
            
            showDialer('Du befindest dich im Nachrichten-Center!');
        }
    })

    // >> change panel ~ works
    .on("click", '[data-action="overview:messages,panel"] .point', function(){
        
        let $t, $c, order, url, react;

        $t = $(this);
        $c = '[data-load="overview:messages"]';
        order = $t.data('order');
        url = dynamicHost + "/_magic_/ajax/content/overview/messages";
        react = $body.find("[data-load='overview:messages']");
        
        if(!$body.hasClass('loading')) {

            $body.addClass('loading');
            Manage.loadMessages(url, order, react, loader);
        }
        
    })

    // open ~ works
    // TODO: do it differently, this sucks
    .on('click', '[data-action="overview:messages,open"]', function(){

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

    })
    
    // read ~ works
    // TODO: do it differently, this sucks
    .on('click', '[data-action="overview:messages,read"]', function(){

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

        let $f = $m.find('[data-form="overview:messages,actions"]');
        let url = dynamicHost + '/_magic_/ajax/functions/messages/actions';
        let id = $t.closest('content-card').data('json')[0].id;
        let dS  = $f.serialize() + '&id=' + id;

        msgAction($f, url, dS);

    // faveorite ~ works
    // TODO: do it differently, this sucks
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

        let $f = $m.find('[data-form="overview:messages,actions"]');
        let url = dynamicHost + '/_magic_/ajax/functions/messages/actions';
        let id = $t.closest('content-card').data('json')[0].id;
        let dS  = $f.serialize() + '&id=' + id;

        msgAction($f, url, dS);

    })
});

// read/favorize ~ works
let msgAction = function(form, ajaxUrl, data) {
    
    let $f = form;
    let url = ajaxUrl;
    let dS  = data;
    
    $.ajax({
        url: url,
        data: dS,
        method: 'POST',
        type: 'TEXT',
        success: function(data) {

            if(!data.status) {
                showDialer(data.message, "inbox", "Nachrichten-Center");
            }
        },
        error: function(data) {
            console.error(data);
        }
    });
}