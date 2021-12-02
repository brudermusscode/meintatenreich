$(function(){

    
    // courses > add
    $(document).on('click', '[data-action="manage:course,add"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/course/add';
        
        let ajax = $.ajax({
            
            url: url,
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

    })  
    
    // courses > add > save
    .on('click', '[data-action="manage:course,add,save"]', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/course/add';
        let dS = $('[data-form="manage:course,add"]').serialize();
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                console.log(data);
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '2':
                        res = 'Der Preis konnte nicht formatiert werden...';
                        break;
                    case '3':
                        res = 'Die Teilnehmerzahl muss nummerisch sein...';
                        break;
                    case 'success':
                        res = 'Hinzugefügt!';
                        setTimeout(function(){
                            window.location.replace(window.location);
                        }, 1000);
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    })
    
    // courses > edit
    .on('click', '[data-action="manage:course"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/course';
        
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

    })
    
    // switch I/O
    .on('click', '[data-action="manage:course,toggle"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/ajax/manage/course/toggle';
        let res;
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data === 'on') {
                    $t.find('.ic i').html('blur_off');
                    $t.find('.ne').html('Deaktivieren');
                    $t.closest('.order').find('.course-status i').removeClass('cred').addClass('cgreen');
                    $t.closest('.order').find('.next-date').slideDown();
                    res = 'Kurs aktiviert!';
                } else if(data === 'off') {
                    $t.find('.ic i').html('blur_on');
                    $t.find('.ne').html('Aktivieren');
                    $t.closest('.order').find('.course-status i').removeClass('cgreen').addClass('cred');
                    $t.closest('.order').find('.next-date').slideUp();
                    res = 'Kurs deaktiviert!';
                } else {
                    res = 'Ein unbekannter Fehler ist aufgetreten...';
                }
                
                closeOverlay($ov, true);
                showDialer(res);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    })
    
    // courses > delete
    .on('click', '[data-action="manage:course,delete"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let $courseCont = $t.closest('content-card');
        let courseContH = $courseCont.height();
        let id = $t.data('json')[0].id;
        let url = '/hk/ajax/manage/course/delete';
        let res;
        
        showDialer('Lösche...');
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data === 'success') {
                    res = 'Kurs gelöscht!';
                    $courseCont.slideUp(300, 'swing');
                } else {
                    res = 'Ein unbekannter Fehler ist aufgetreten...';
                }
                
                closeOverlay($ov, true);
                showDialer(res);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    })
    
    // dates > add
    .on('click', '[data-action="manage:course,dates,add"]', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let id = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/course/dates/add';
        let dS = $('[data-form="manage:course,edit"]').serialize() + '&id=' + id;
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                console.log(data);
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '2':
                        res = 'Das Datum hat ein falsches Format: Y-m-d...';
                        break;
                    case '3':
                        res = 'Die Zeit hat ein falsches Format: 00:00...';
                        break;
                    case 'success':
                        
                            let $c = $(document).find('[data-react="manage:courses,date,add"]');
                            let ajax2 = $.ajax({
                            url: '/hk/get/elements/manage/courses/date',
                            data: dS,
                            method: 'POST',
                            type: 'HTML',
                            success: function(data){
                                
                                $c.prepend(data);
                                
                                console.log(data);
                                
                            }
                        });
                        res = 'Hinzugefügt!';
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    })
    
    // dates > delete
    .on('click', '[data-action="manage:course,dates,delete"]', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let $courseCont = $t.closest('content-card');
        let courseContH = $courseCont.height();
        let id = $t.data('json')[0].id;
        let couid = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/course/dates/delete';
        let res;
        
        showDialer('Lösche...');
        
        let ajax = $.ajax({
            
            url: url,
            data: { id: id, couid: couid },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data === 'success') {
                    res = 'Termin gelöscht!';
                    $courseCont.slideUp(300, 'swing');
                } else {
                    res = 'Ein unbekannter Fehler ist aufgetreten...';
                }
                
                closeOverlay($ccOv, false);
                showDialer(res);
                
            },
            error: function(data) {
                // SET ERROR TEXT
            }
            
        });

    })
    
    // dates > edit
    .on('click', '[data-action="manage:course,dates"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/course/dates';
        
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
                // SET ERROR REPORT
            }
            
        });

    })

    // dates > edit > save
    .on('click', '[data-action="manage:course,edit,save"]', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let id = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/course/edit';
        let dS = $('[data-form="manage:course,edit"]').serialize() + '&id=' + id;
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        break;
                    case '2':
                        res = 'Die gewählte Kategorie existiert nicht...';
                        break;
                    case 'success':
                        res = 'Gespeichert!';
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    })

});