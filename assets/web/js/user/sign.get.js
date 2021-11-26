$(function() {

    let body = $("body");

    $(document).on('click', '[data-action="open-login"], [data-action="open-signup"]', function(){

        var t = $(this);
        var tdata = t.data('json');
        tdata = tdata[0].open;
        var action;
        var url;
        if(tdata === 'login') {
            action = 'open-login';
            url = '/ajax/popups/sign/in';
        } else {
            action = 'open-signup';
            url = '/ajax/popups/sign/up';
        }
        addOverlay(body);
        var overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        var loader = $('loader').parent();

        $.ajax({

            type: 'TEXT',
            method: 'POST',
            url: url,
            data: { action: action },
            success: function(data) {

                loader.remove();
                overlay.append(data);

            }

        });

    });

});