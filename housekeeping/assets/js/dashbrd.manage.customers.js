$(function(){

    // get customer overview
    $(document).on('click', '[data-action="manage:customers,overview"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/customers/overview';
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data, status) {
                
                $lo.remove();
                $ov.append(data);
                
            }
            
        });

    });
    
    // get customer orders
    $(document).on('click', '[data-action="manage:customers,orders"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/customers/orders';
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                $lo.remove();
                $ov.append(data);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    });

});