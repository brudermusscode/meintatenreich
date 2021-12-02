$(function(){

    $(document).on('click', '[data-action="manage:order"]', function(){
        
        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/orders/order';
        
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
        
    })
    
    // * ORDER: Change Status * //
    .on('click', '[data-action="manage:order,changestatus"] datalist ul li', function(){
    
        let $t = $(this);
        let res;
        let $wc = $('wide-container[data-json]');
        let $co = $t.closest('content-card');
        let url = '/hk/ajax/manage/orders/changestatus';
        let id = $wc.data('json')[0].id;
        let st = $t.data('json')[0].status;
        let dS = { id: id, status: st };
        let $btn = $t.closest('[data-action="manage:order,changestatus"]');
    
        // ADD OVERLAY AND LOADER
        addOverlay('255,255,255', $co, '%', false);
        let $wcOv = $co.find('page-overlay');
        addLoader('color', $wcOv);
        let $wcLo = $wcOv.find('color-loader');
    
        showDialer('Status wird geändert...');
    
        // AJAX CALL
        let ajax = $.ajax({
            url: url,
            data: dS,
            type: 'TEXT',
            method: 'POST',
            success: function(data){
    
                switch(data) {
                    case '0':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten';
                        break;
                    case '1':
                        res = 'Diese Bestellung existiert nicht'
                        break;
                    case 'success':
                        res = 'Status wurde geändert und der Kunde wurde informiert';
                        $btn.removeClass('got done canceled sent');
                        switch(st) {
                            case 'got':
                                $btn.addClass('got');
                                $btn.find('.inr .ic:first-of-type i').html('new_releases');
                                $btn.find('.te').html('Neu');
                                break;
                            case 'done':
                                $btn.addClass('done');
                                $btn.find('.inr .ic:first-of-type i').html('done');
                                $btn.find('.te').html('Abgeschlossen');
                                break;
                            case 'sent':
                                $btn.addClass('sent');
                                $btn.find('.inr .ic:first-of-type i').html('watch_later');
                                $btn.find('.te').html('Unterwegs');
                                break;
                            case 'canceled':
                                $btn.addClass('canceled');
                                $btn.find('.inr .ic:first-of-type i').html('clear');
                                $btn.find('.te').html('Storniert');
                        }
                        
                        closeOverlay($wcOv);
                }
    
                showDialer(res);
    
    
            }
        });
    
    })
    
    // * ORDER: Paid * //
    .on('click', '[data-action="manage:order,paid"] datalist ul li', function(){
    
        let $t = $(this);
        let res;
        let $wc = $('wide-container[data-json]');
        let $co = $t.closest('content-card');
        let url = '/hk/ajax/manage/orders/paid';
        let id = $wc.data('json')[0].id;
        let st = $t.data('json')[0].status;
        let dS = { id: id, status: st };
        let $btn = $t.closest('[data-action="manage:order,paid"]');
    
        // ADD OVERLAY AND LOADER
        addOverlay('255,255,255', $co, '%', false);
        let $wcOv = $co.find('page-overlay');
        addLoader('color', $wcOv);
        let $wcLo = $wcOv.find('color-loader');
    
        showDialer('Status wird geändert...');
    
        // AJAX CALL
        let ajax = $.ajax({
            url: url,
            data: dS,
            type: 'TEXT',
            method: 'POST',
            success: function(data){
    
                switch(data) {
                    case '0':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten';
                        break;
                    case '1':
                        res = 'Diese Bestellung existiert nicht'
                        break;
                    case 'success':
                        res = 'Status wurde geändert und der Kunde wurde informiert';
                        $btn.addClass('ok');
                        $btn.find('.inr .ic:first-of-type i').html('check_box');
                        $btn.find('.te').html('Bezahlt');
                        
                        closeOverlay($wcOv);
                }
    
                showDialer(res);
    
    
            }
        });
    
    });
});