$(function(){

    let $body = $("body");

    // open ~ works
    $(document).on('click', '[data-action="manage:order"]', function(){
        
        let $t, id, url;

        // add new overlay
        overlay = Overlay.add($body, true);
        
        $t = $(this),
        id = $t.data('json')[0].id,
        url = '/_magic_/ajax/content/manage/orders';
        
        $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {

                overlay.loader.remove();
                overlay.overlay.append(data);
                
            },
            error: function(data) {
                console.error(data);
            }
            
        });
        
    })
    
    // update status ~ works
    .on('click', '[data-action="manage:order,changestatus"] datalist ul li', function(){
    
        let $t, $wc, $co, $btn, formData, url;

        $t = $(this);
        $wc = $('wide-container[data-json]');
        $co = $t.closest('content-card');
        $btn = $t.closest('[data-action="manage:order,changestatus"]');
        formData = new FormData();
        formData.append("id", $wc.data('json')[0].id);
        formData.append("status", $t.data('json')[0].status);
        url = dynamicHost + "/_magic_/ajax/functions/manage/orders/status";

        showDialer('Status wird geändert...');

        let ajax = $.ajax({
            url: url,
            data: formData,
            method: 'POST',
            type: 'JSON',
            contentType: false,
            processData: false,
            success: function(data){

                console.log(data);

                if(data.status) {

                    $btn.removeClass('got done canceled sent');

                    /*
                       !looks like shit, please smooth out
                    */

                    switch(data.set) {
                        case 'got':
                            $btn.addClass('got');
                            break;
                        case 'done':
                            $btn.addClass('done');
                            break;
                        case 'sent':
                            $btn.addClass('sent');
                            break;
                        case 'canceled':
                            $btn.addClass('canceled');
                    }
                }

                showDialer(data.message + " (" + data.set + ")", "euro", "Bestellungen");
    
    
            },
            error: function(data) {
                console.error(data);
            }
        });
    
    })
    
    // update payment progress ~ works
    .on('click', '[data-action="manage:order,paid"] datalist ul li', function(){
    
        let $t, $wc, $co, $btn, formData, url;

        $t = $(this);
        $wc = $('wide-container[data-json]');
        $co = $t.closest('content-card');
        $btn = $t.closest('[data-action="manage:order,paid"]');
        url = dynamicHost + '/_magic_/ajax/functions/manage/orders/payment';
        formData = new FormData();
        formData.append("id", $wc.data('json')[0].id);
        formData.append("status", $t.data('json')[0].status);
    
        for (var [key, value] of formData.entries()) { 
            console.log(key, value);
        }

        showDialer('Zahlungsstatus wird geändert...');
    
        let ajax = $.ajax({
            url: url,
            data: formData,
            method: "POST",
            type: 'JSON',
            contentType: false,
            processData: false,
            success: function(data){
    
                console.log(data);

                if(data.status) {
                    $btn.addClass('ok');
                    $btn.find('.inr .ic:first-of-type i').html('check_box');
                    $btn.find('.te').html('Bezahlt');
                }
    
                showDialer(data.message, "euro", "Bestellungen");
            },
            error: function(data) {
                console.error(data);
            }
        });
    
    });
});