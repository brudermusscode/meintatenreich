$(function(){

    // upload images with these arrays
    let uploadImagesArray = [];
    let uploadImagesErrorArray = [];

    // categories > add
    $(document).on('click', '[data-action="manage:products,category,add"]', function(){
        
        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/product/category/add';
        
        let ajax = $.ajax({
            
            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data, status) {
                
                $lo.remove();
                $ov.append(data);
                
            }
            
        });
        
    })
    
    // categories > add > save
    .on('click', '[data-action="manage:products,category,add,save"]', function(){
        
        // HANDLE OVERLAY
        let $ov = $bod.find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/category/add';
        let $f = $('[data-form="manage:products,category,add"]');
        let $r = $('[data-react="manage:products,category,add"]');
        let dS = $f.serialize();
        
        showDialer('Speichern...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                switch(data){
                    case '0':
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten...';
                        closeOverlay($ccOv, false);
                        break;
                    case '1':
                        res = 'Diese Kategorie existiert bereits...';
                        closeOverlay($ccOv, false);
                        break;
                    case 'success':
                        res = 'Hinzugefügt!';
                        $r.prepend('<content-card class="lt mr8 mb8"><div class="normal-box adjust"><div class="ph24 lh36"><p class="fw4" style="white-space:nowrap;">' + $f.find('input[name="name"]').val() + '</p></div></div></content-card>');
                        closeOverlay($ov, true);
                }
                
                showDialer(res);

            }

        });
        
    })
    
    // categories > edit
    .on('click', '[data-action="manage:products,category,edit"]', function(){
        
        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/product/category/edit';
        let id = $t.data('json')[0].id;
        let dS = { id: id };
        let res;
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                $lo.remove();
                $ov.append(data);

            }

        });
        
    })
    
    // categories > edit > save
    .on('click', '[data-action="manage:products,category,edit,confirm"]', function(){
        
        // HANDLE OVERLAY
        let $ov = $bod.find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/category/edit';
        let $f = $('[data-form="manage:products,category,edit"]');
        let dS = $f.serialize();
        
        showDialer('Speichere...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'TEXT',
            success: function(data){
                
                console.log(data);
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein Fehler ist aufgetreten...';
                        closeOverlay($ccOv, false);
                        break;
                    case 'success':
                        res = 'Gespeichert!';
                        closeOverlay($ccOv, false);
                }
                
                showDialer(res);

            }

        });
        
    })
    
    // categories > delete
    .on('click', '[data-action="manage:products,category,edit,remove"]', function(){
        
        let $t = $(this);
        let $tp = $t.find('p');
        let co = $t.data('color');
        
        $tp.addClass('opa0');
        
        setTimeout(function(){
            $tp.css('color', 'white').text('Ganz sicher?').removeClass('opa0');
            $t.css('background', co);
            $t.attr('data-action', 'manage:products,category,edit,remove,confirm');
        }, 200);
        
    })
    
    // categories > delete > save
    .on('click', '[data-action="manage:products,category,edit,remove,confirm"]', function(){
        
        // HANDLE OVERLAY
        let $ov = $bod.find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/category/edit/delete';
        let $f = $('[data-form="manage:products,category,edit"]');
        let dS = $f.serialize();
        
        showDialer('Lösche...');
        
        let ajax = $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'TEXT',
            success: function(data){
                
                switch(data){
                    case '0':
                    case '1':
                    default:
                        res = 'Ein Fehler ist aufgetreten...';
                        closeOverlay($ccOv, false);
                        break;
                    case 'success':
                        res = 'Gelöscht, bitte warten...';
                        window.location.replace(window.location);
                }
                
                showDialer(res);

            }

        });
        
    })

    // add product
    .on('click', '[data-action="manage:products,add"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let url = '/hk/get/manage/product/add';
        
        let ajax = $.ajax({

            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                $lo.remove();
                $ov.append(data);

            }

        });

    })

    // add > upload images
    .on('click', '#upload-new-images', function(e){
        
        let $t = $(this);
        let cas;
        let res;
        let addToArrayError;
        let $c = $t.closest('[data-react="manage:products,add,addImage,show"]');

        addOverlay('255,255,255', $c, '%', false);
        let $cOv = $c.find('page-overlay');
        addLoader('color', $cOv);
        let $covLo = $cOv.find('color-loader');
        
        let chosen = false;
        
        $('#upload-new-images').fileupload({

            url: '/hk/ajax/manage/product/add/uploadimage',
            dataType: 'json',
            autoUpload: false,
            add: function(e, data) {

                chosen = true;
                
                let fileTypeAllowed = /.\.(gif|jpg|jpeg|png)$/i;
                let fileName = data.originalFiles[0].name;

                if(!fileTypeAllowed.test(fileName)) {
                    showDialer('Die ausgewählten Bilder haben ein unzulässiges Format (JPG, JPEG, PNG, GIF).');
                } else {
                    data.submit();
                }

            },
            done: function(e, data) {

                let resTxt = data.jqXHR.responseJSON;
                let url = resTxt.url;
                let array = $('[data-react="manage:products,add,addImage,imgArray"][name]');

                switch(resTxt.status) {
                    case '0':
                    default:
                        cas = 0;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        break;
                    case '1':
                        cas = 1;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        let add = uploadImagesArray.push(url);
                        
                        let ajax = $.ajax({
                            url: '/hk/get/elements/manage/products/addimage',
                            data: { url: url },
                            method: 'POST',
                            type: 'HTML',
                            success: function(data){
                                
                                $c.prepend(data);
                                
                            }
                        });
                        
                }
                
            },
            progressall: function(e, data) {

                let progress = parseInt(data.loaded / data.total * 100, 10);
                showDialer('Lade hoch...');

            },
            stop: function(data) {
                
                let arLen = uploadImagesErrorArray.length;
                
                if(uploadImagesErrorArray.indexOf(0) > -1) {
                    res = 'Nicht alle Bilder konnten hochgeladen werden...';
                    closeOverlay($cOv, false);
                } else {
                    res = 'Alle hinzugefügt!';
                    closeOverlay($cOv, false);
                }
                
                $('[data-react="manage:products,add,addImage,gallery,info"]').css({
                    opacity:'1',
                    visibility:'visible',
                    bottom:'-24px'
                });
                
                uploadImagesErrorArray = [];
                
                showDialer(res);
                
            }

        });
        
//        if(chosen === false) {
//            closeOverlay($cOv, false);
//        }
        
    })

    // add > save
    .on('click', '[data-action="manage:products,add,save"]', function(){

        // HANDLE OVERLAY
        let $ov = $(document).find('page-overlay');
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let url = '/hk/ajax/manage/product/add/save';
        let $f = $ov.find('[data-form="manage:products,add"]');
        let dS = $f.serialize() + '&images=' + uploadImagesArray;
        
        if(checkForm($f) === false) {
            
            showDialer('Bitte fülle alle relevanten felder aus!');
            closeOverlay($ccOv, false);
            
        } else {
        
            let ajax = $.ajax({

                url: url,
                data: dS,
                method: 'POST',
                type: 'HTML',
                success: function(data){
                    
                    switch(data){
                        case '':
                            res = 'Bitte fülle alle Felder aus...'
                            closeOverlay($ccOv, false);
                            break;
                        case '0':
                        default:
                            res = 'Ein unbekannter Fehler ist aufgetreten';
                            closeOverlay($ccOv, false);
                            break;
                        case '1':
                            res = 'Ein Bild wurde nicht richtig hochgeladen. Bitte lade die Seite neu und versuche es erneut...';
                            closeOverlay($ccOv, false);
                            break;
                        case '2':
                            res = 'Die gewählte Produktkategorie existiert nicht...';
                            closeOverlay($ccOv, false);
                            break;
                        case '3':
                            res = 'Der Preis ist unzulässig...';
                            closeOverlay($ccOv, false);
                            break;
                        case '4':
                            res = 'Wähle ein Hauptbild aus...';
                            closeOverlay($ccOv, false);
                            break;
                        case 'success':
                            res = 'Produkt hinzugefügt!';
                            closeOverlay($ov, true);
                            clearArray(uploadImagesArray);
                            clearArray(uploadImagesErrorArray);
                            console.log(uploadImagesArray, uploadImagesErrorArray);
                            setTimeout(function(){
                                window.location.replace(window.location);
                            }, 2600);
                    }
                    
                    showDialer(res);

                }

            });
            
        }

    })

    // add > change gallery
    .on('click', '[data-action="manage:products,add,addImage,gallery"] .item', function(){
        
        let $t = $(this);
        let url;
        let $r = $('[data-react="manage:products,add,addImage,gallery"]');
        let $c = $t.closest('.product-overview');
        let $h = $('[data-react="manage:products,add,addImage,gallery,info"]');
        
        if(!$t.hasClass('add-new')) {
            
            url = $t.data('json')[0].id;
        
            $c.find('.item').each(function() {
                let $e = $(this);
                $e.removeClass('gal');
            });

            $t.addClass('gal');
            $h.css({ opacity:'0', 'visibility':'hidden', bottom:'-42px' });
            $r.val(url);
            
        }
        
    })
    
    // edit
    .on('click', '[data-action="manage:products,edit"]', function(){

        // HANDLE OVERLAY
        addOverlay('255,255,255', $bod);
        let $ov = $bod.find('page-overlay');
        addLoader('color', $ov);
        let $lo = $ov.find('color-loader');
        
        let $t = $(this);
        let id = $t.data('json')[0].id;
        let url = '/hk/get/manage/product/edit';
        
        let ajax = $.ajax({

            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data){
                
                $lo.remove();
                $ov.append(data);

            }

        });

    })

    // edit > upload images
    .on('click', '[data-action="manage:products,edit,addImage"]', function(){

        let $t = $(this);
        let cas;
        let res;
        let addToArrayError;
        let $c = $t.closest('[data-react="manage:products,edit,addImage,show"]');
        let id = $c.closest('wide-container').data('json')[0].id;

        addOverlay('255,255,255', $c, '%', false);
        let $cOv = $c.find('page-overlay');
        addLoader('color', $cOv);
        let $covLo = $cOv.find('color-loader');
        
        let chosen = false;
        
        $('#image-penetration').fileupload({

            url: '/hk/ajax/manage/product/edit/uploadimage',
            dataType: 'json',
            formData: { id: id },
            autoUpload: false,
            add: function(e, data) {

                chosen = true;
                
                let fileTypeAllowed = /.\.(gif|jpg|jpeg|png)$/i;
                let fileName = data.originalFiles[0].name;

                if(!fileTypeAllowed.test(fileName)) {
                    showDialer('Die ausgewählten Bilder haben ein unzulässiges Format (JPG, JPEG, PNG, GIF).');
                } else {
                    data.submit();
                }

            },
            done: function(e, data) {
                
                let resTxt = data.jqXHR.responseJSON;
                let id = resTxt.id;
                let url = resTxt.url;
                
                switch(resTxt.status) {
                    case '':
                    case '0':
                    default:
                        cas = 0;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        break;
                    case '1':
                        cas = 1;
                        addToArrayError = uploadImagesErrorArray.push(cas);
                        
                        let ajax = $.ajax({
                            url: '/hk/get/elements/manage/products/addimage',
                            data: { id: id, url: url },
                            method: 'POST',
                            type: 'HTML',
                            success: function(data){
                                
                                $c.prepend(data);
                                console.log(uploadImagesArray, uploadImagesErrorArray);
                                
                            }
                        });
                        
                }
                
            },
            progressall: function(e, data) {

                let progress = parseInt(data.loaded / data.total * 100, 10);
                showDialer('Lade hoch...');

            },
            stop: function(data) {
                
                let arLen = uploadImagesErrorArray.length;
                
                if(uploadImagesErrorArray.indexOf(0) > -1) {
                    res = 'Nicht alle Bilder konnten hochgeladen werden...';
                    closeOverlay($cOv, false);
                } else {
                    res = 'Alle hinzugefügt!';
                    closeOverlay($cOv, false);
                }
                
                uploadImagesErrorArray = [];
                
                showDialer(res);
                
            }

        });
        
        if(chosen === false) {
            closeOverlay($cOv, false);
        }

    })

    // edit > save
    .on('click', '[data-action="manage:products,edit,save"]', function(){

        // HANDLE OVERLAY
        let $cc = $(this).closest('content-card');
        addOverlay('255,255,255', $cc, '%', false);
        let $ccOv = $cc.find('page-overlay');
        addLoader('color', $ccOv);
        
        let $t = $(this);
        let res;
        let id = $t.closest('wide-container').data('json')[0].id;
        let url = '/hk/ajax/manage/product/edit';
        let dS = $('[data-form="manage:products,edit"]').serialize() + '&id=' + id;
        
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
                        clearArray(uploadImagesArray);
                        clearArray(uploadImagesErrorArray);
                        console.log(uploadImagesArray, uploadImagesErrorArray);
                }
                
                closeOverlay($ccOv, false);
                
                showDialer(res);

            }

        });

    })

    // edit > change gallery
    .on('click', '[data-action="manage:products,edit,addImage,gallery"] .item', function(){
        
        let $t = $(this);
        let id;
        let $r = $('[data-react="manage:products,edit,addImage,gallery"]');
        let $c = $t.closest('.product-overview');
        
        if(!$t.hasClass('add-new')) {
            
            id = $t.data('json')[0].id;
        
            $c.find('.item').each(function() {
                let $e = $(this);
                $e.removeClass('gal');
            });

            $t.addClass('gal');
            $r.val(id);
            
        }
        
    })

});