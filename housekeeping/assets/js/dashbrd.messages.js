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
                            window.location.replace('messages');
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

    // >> change panel
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
        
    });
});