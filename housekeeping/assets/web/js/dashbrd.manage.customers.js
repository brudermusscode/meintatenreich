$(function(){

    let $body = $("body"), $t, url, formData;

    // get customer overview ~ works
    $(document).on('click', '[data-action="manage:customers,overview"]', function(){

        let id;

        // add new overlay
        overlay = Overlay.add($body, true);
        
        $t = $(this);
        id = $t.data('json')[0].id;
        url = '/_magic_/ajax/content/manage/customers/overview';
        
        $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {

                if(data !== 0) {
                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {

                    showDialer("OppsieWoopsieLoopsie");
                }
            }
        });
    })
    
    // get customer orders ~ works
    .on('click', '[data-action="manage:customers,orders"]', function(){

        // add new overlay
        overlay = Overlay.add($body, true);
        
        $t = $(this);
        formData = new FormData();
        formData.append("id", $t.data('json')[0].id);
        url = dynamicHost + '/_magic_/ajax/content/manage/customers/orders';
        
        $.ajax({
            
            url: url,
            data: formData,
            method: 'POST',
            type: 'HTML',
            contentType: false,
            processData: false,
            success: function(data) {

                if(data !== 0) {
                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {
                    showDialer("Oopsie");
                }
                
            },
            error: function(data) {
                console.error(data);
            }
        });
    })

    // send custom mail ~ works
    .on('click', '[data-action="mail:custom"]', function(){
    
        // add new overlay
        overlay = Overlay.add($body, true);

        $t = $(this);
        let rel = $t.data('json')[0].rel;
        let which = $t.data('json')[0].which;
        url = dynamicHost + '/_magic_/ajax/content/overview/messages/mail';
        let dS;
        
        if(which === 'customer'){
            dS = { rel: rel };
        } else {
            dS = { rel: rel };
        }
        
        $.ajax({
            
            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data !== 0) {

                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {

                    showDialer("Da ist was schief gelaufen");
                }
            }
        });
    })

    // TODO: send custom mail >> submit
    .on('submit', '[data-form="messages:mail"]', function(){
        
        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);

        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/functions/overview/messages/mail';
        formData = new FormData(this);

        $.ajax({
            
            url: url,
            data: formData,
            method: 'POST',
            type: 'JSON',
            processData: false,
            contentType: false,
            success: function(data,) {
                
                console.log(data);

                if(data.status) {

                    Overlay.close($body);
                } else {
                    
                    Overlay.close(overlay.overlay.parent());
                }
                
                showDialer(data.message);
            },
            error: function(data) {
                console.error(data);
            }
        });
    });
});