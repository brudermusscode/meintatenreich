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
                    responser("Nothing");
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
        
        responser('Speichere...');
        
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
                
                responser(data.message);
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
                    responser("Nothing");
                }
                
            },
            error: function(data) {
                console.error(data);
            }
            
        });

    })

    // edit > submit ~ works
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
        
        responser('Speichere...');
        
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
                
                responser(data.message);
            },
            error: function(data) {
                console.error(data);
            }
        });
    })
    
    // toggle activated/deactivated ~ works
    .on('click', '[data-action="manage:courses,toggle"]', function(){
        
        $t = $(this);
        id = $t.data('json')[0].id;
        url = dynamicHost + '/_magic_/ajax/functions/manage/courses/toggle';
        let $contentCard = $t.closest("content-card");
        
        $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                if(data.status) {
                    $t.toggleClass("activate deactivate");
                    $contentCard.toggleClass("activated deactivated");
                }

                responser(data.message);
            },
            error: function(data) {
                console.error(data);
            }
            
        });

    })
    
    // courses > archive/unarchive ~ works
    .on('click', '[data-action="manage:courses,archive"]', function(){

        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/functions/manage/courses/archive';
        let $contentCard = $t.closest("content-card");
        id = $contentCard.data('json')[0].id;
        
        $.ajax({
            
            url: url,
            data: { id: id },
            method: 'POST',
            type: 'JSON',
            success: function(data) {
                
                if(data.status) {

                    $t.toggleClass("archive unarchive");
                    $contentCard.css({
                        opacity: "0",
                        visibility: "hidden"
                    });

                    setTimeout(function() {
                        $contentCard.remove();
                    }, 400);
                }

                responser(data.message);
            },
            error: function(data) {
                console.error(data);
            }
            
        });

    })

    // dates > edit ~ works
    .on('click', '[data-action="manage:courses,dates"]', function(){

        // add new overlay
        overlay = Overlay.add($body, true);

        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/content/manage/courses/dates/add';
        let id = $t.closest("content-card").data("json")[0].id;
        
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
                    responser("Nothing");
                }
                
            },
            error: function(data) {
                console.error(data);
            }
            
        });

    })

    // dates > edit > submit ~ works
    .on('submit', '[data-form="manage:courses,dates"]', function(){

        let $c;

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);

        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/functions/manage/courses/dates/add';
        formData = new FormData(this);
        
        responser('Speichere...');
        
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

                    $c = $(document).find('[data-react="manage:courses,dates"]');
                    url = dynamicHost + "/_magic_/ajax/elements/courses/date";

                    $.ajax({

                        url: url,
                        data: formData,
                        method: 'POST',
                        type: 'HTML',
                        contentType: false,
                        processData: false,
                        success: function(data){

                            if(data !== 0) {
                                $c.prepend(data);
                            } else {
                                responser("Etwas lief schief, dennoch wurde der Termin hinzugefügt");
                            }

                            Overlay.close(overlay.overlay.parent());
                        }
                    });

                } else {
                    Overlay.close(overlay.overlay.parent());
                }
                
                responser(data.message);
            },
            error: function(data) {
                console.error(data);
                Overlay.close(overlay.overlay.parent());
            }
        });
    })
    
    // dates > edit > delete
    .on('click', '[data-action="manage:courses,dates,delete"]', function(){
        
        let id, cid, $courseCont, courseContH;

        $t = $(this);
        $courseCont = $t.closest('content-card');
        courseContH = $courseCont.height();
        id = $t.data('json')[0].id;
        cid = $t.closest('wide-container').data('json')[0].id;
        url = dynamicHost + '/_magic_/ajax/functions/manage/courses/dates/delete';
        
        responser('Lösche...');
        
        $.ajax({
        
            url: url,
            data: { id: id, cid: cid },
            method: 'POST',
            type: 'JSON',
            success: function(data) {

                console.log(data);

                if(data.status) {

                    $courseCont.slideUp(300, 'swing');
                }

                responser(data.message);
            },
            error: function(data) {
                console.error(data);
            }
        });
    });

    function responser(text) {
        return showDialer(text, "golf_course", "Kurse");
    }

});