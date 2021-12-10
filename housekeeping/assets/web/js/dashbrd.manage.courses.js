$(function(){

    let $body = $("body"), $t, url, overlay, $append, formData;
    
    // add ~ works
    $(document).on('click', '[data-action="manage:courses,add"]', function(){

        // add new overlay
        overlay = Overlay.add($body, true);

        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/content/manage/courses/add';
        
        $.ajax({
            
            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data !== 0) {
                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {
                    showDialer("Nothing");
                }
                
            },
            error: function(data) {
                console.error(data);
            }
            
        });

    })  
    
    // add > submit ~ works
    .on('submit', '[data-form="manage:courses,add"]', function(){

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);

        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/functions/manage/courses/add';
        formData = new FormData(this);
        
        showDialer('Speichern...');
        
        $.ajax({

            url: url,
            data: formData,
            method: 'POST',
            type: 'JSON',
            contentType: false,
            processData: false,
            success: function(data){
                
                if(data.status) {
                    setTimeout(function(){
                        window.location.replace(window.location);
                    }, 1000);
                } else {
                    Overlay.close(overlay.overlay.parent());
                }
                
                showDialer(data.message);
            },
            error: function(data) {
                console.error(data);
            }
        });
    })
    
    // courses > edit ~ works
    .on('click', '[data-action="manage:courses,edit"]', function(){

        // add new overlay
        overlay = Overlay.add($body, true);

        $t = $(this);
        id = $t.data('json')[0].id;
        url = dynamicHost + '/_magic_/ajax/content/manage/courses/edit';
        
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
                    showDialer("Nothing");
                }
                
            },
            error: function(data) {
                console.error(data);
            }
            
        });

    })

    // edit > submit
    .on('submit', '[data-form="manage:courses,edit"]', function(){

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        $t = $(this);
        id = $t.closest('wide-container').data('json')[0].id;
        url = dynamicHost + "/_magic_/ajax/functions/manage/courses/edit";
        formData = new FormData(this);
        formData.append("id", id);
        
        showDialer('Speichern...');
        
        $.ajax({

            url: url,
            data: formData,
            method: 'POST',
            type: 'JSON',
            contentType: false,
            processData: false,
            success: function(data){
                
                console.log(data);

                if(data.status) {
                    setTimeout(function(){
                        window.location.replace(window.location);
                    }, 1000);
                } else {
                    Overlay.close(overlay.overlay.parent());
                }
                
                showDialer(data.message);
            },
            error: function(data) {
                console.error(data);
            }
        });
    })
    
    // switch I/O
    .on('click', '[data-action="manage:courses,toggle"]', function(){

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

});