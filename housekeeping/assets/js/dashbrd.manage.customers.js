$(function(){

    let $body = $("body");

    // get customer overview ~ works
    $(document).on('click', '[data-action="manage:customers,overview"]', function(){

        let id, url, $t;

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

        let $t, formData, url;

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
    });
});