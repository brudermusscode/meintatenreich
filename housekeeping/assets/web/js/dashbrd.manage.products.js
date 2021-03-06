$(function(){

    let $body;

    $body = $("body");

    $(document)

    // open search explorer for images
    .on("click", '[data-action="manage:products,add,addImage"]', function(){
        
        let $i = $(document).find('[data-form="uploadFiles:products,add"] input[type="file"]').click();
        
    })

    // categories > add ~ works
    .on('click', '[data-action="manage:products,category,add"]', function(){
        
        let overlay, url;

        // add new overlay
        overlay = Overlay.add($body, true);

        // set url for xhr request
        url = dynamicHost + '/_magic_/ajax/content/manage/products/categories/add';
        
        $.ajax({
            
            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data,) {
                
                if(data !== 0) {
                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {
                    responser("BRRRRRA! Bruder wieso? Wieso, wieso, wieso?");
                }
            }
        });
    })
    
    // categories > add > save ~ works
    .on('submit', '[data-form="manage:products,category,add"]', function(e){
        
        e.preventDefault();

        let $t, $r, url, formData, closeOverlay;

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        $t = $(this);
        $r = $('[data-react="manage:products,category,add"]');
        url = dynamicHost + '/_magic_/ajax/functions/manage/products/categories/add';
        formData = new FormData(this);
        
        $.ajax({

            url: url,
            data: formData,
            method: 'POST',
            type: 'JSON',
            contentType: false,
            processData: false,
            success: function(data){

                if(data.status) {
                    res = 'Hinzugefügt!';
                    $r.prepend('<content-card class="lt mr8 mb8" data-id="' + data.id + '" data-element="products:category"><div class="normal-box adjust curpo lh36 ph12" data-json=\'[{"id":"' + data.id + '"}]\' data-action="manage:products,category,edit"><div><p class="fw4" style="white-space:nowrap;">' + $t.find('input[name="name"]').val() + '</p></div></div></content-card>');
                    closeOverlay = Overlay.close($body);
                } else {
                    closeOverlay = Overlay.close(overlay.overlay.parent());
                }

                responser(data.message);

            },
            error: function(data) {
                console.error(data);
            }

        });
        
        return false;
    })
    
    // categories > edit ~ works
    .on('click', '[data-action="manage:products,category,edit"]', function(){
        
        let overlay, url, id, $t;

        // new overlay
        overlay = Overlay.add($body, true);
        
        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/content/manage/products/categories/edit';
        id = $t.data('json')[0].id;
        
        $.ajax({

            url: url,
            data: { id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data){

                if(data != 0) {
                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {
                    responser("WOOH BRRRRA");
                    overlay = Overlay.close($body);
                }

            },
            error: function(data) {
                console.error(data);
            }

        });
        
    })
    
    // categories > edit > save ~ works
    // TODO: change category label after editing submission (on success ofc)
    .on('submit', '[data-form="manage:products,category,edit"]', function(e){
        
        e.preventDefault();

        let closeOverlay, overlay, url, $t, $append;

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        $t = $(this);
        formData = new FormData(this);
        url = dynamicHost + '/_magic_/ajax/functions/manage/products/categories/edit';
        
        $.ajax({

            url: url,
            data: formData,
            method: 'POST',
            type: 'JSON',
            contentType: false,
            processData: false,
            success: function(data){
                
                if(data.status) {
                    closeOverlay = Overlay.close($body);
                } else {
                    closeOverlay = Overlay.close(overlay.overlay.parent());
                }
                
                responser(data.message);

            },
            error: function(data) {
                console.log(data);
            }

        });
        
        return false;
    })
    
    // categories > delete ~ works
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
    
    // categories > delete > save ~ works
    .on('click', '[data-action="manage:products,category,edit,remove,confirm"]', function(e){
        
        e.preventDefault();

        let closeOverlay, overlay, url, id, $t, $f, $card, $append;

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        $t = $(this);
        id = $t.data("id");
        url = '/_magic_/ajax/functions/manage/products/categories/delete';
        $f = $('[data-form="manage:products,category,edit"]');
        $card = $("[data-element='products:category'][data-id='" + id + "']").children();

        dS = $f.serialize();
        
        $.ajax({

            url: url,
            data: dS,
            method: 'POST',
            type: 'TEXT',
            success: function(data){
                
                console.log(data, "hi");
                
                if(data.status) {
                    closeOverlay = Overlay.close($body);
                    $card.css({
                        background:"#c48b9f",
                        color:"white",
                        transition:"all .4s linear"
                    });

                    setTimeout(function(){
                        $card.fadeOut(400, function(){
                            $(this).parent().remove();
                        });
                    }, 1000);

                } else {
                    closeOverlay = Overlay.close(overlay.overlay.parent());
                }
                
                responser(data.message);

            },
            error: function(data) {
                console.log(data);
            }
        });
        
    })

    // add product ~ works
    .on('click', '[data-action="manage:products,add"]', function(){
        
        let overlay, url;

        // add new overlay
        overlay = Overlay.add($body, true);

        // set url for xhr request
        url = dynamicHost + '/_magic_/ajax/content/manage/products/add';
        
        $.ajax({
            
            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data,) {
                
                if(data !== 0) {
                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {
                    responser("BRRRRRA! Bruder wieso? Wieso, wieso, wieso?");
                }
            }
        });
    })

    // add > upload images ~ works partially
    .on('change', '[data-form="uploadFiles:products,add"]', function(e){

        let $t, $append, overlay, uri, react;

        $t = $(this),
        uri = dynamicHost + '/_magic_/ajax/functions/manage/products/upload-images';

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        // start new fileupload (jquery)
        if($t.length > 0) {

            react = '[data-react="manage:products,add,addImage,show"]';
            store = '[data-react="uploadFiles:upload-new-files"] input[name="store"]';
            info = $('[data-react="manage:products,add,addImage,gallery,info"]');

            // upload it!
            uploadProductImages(uri, $(this).find("input[type='file']"), react, overlay.overlay.parent(), store);

            info.css({
                opacity:"1",
                visibility:"visible",
                bottom:"-24px"
            });
        }
    })

    // add > save ~ works
    .on('submit', '[data-form="manage:products,add"]', function(e){

        e.preventDefault();

        let $t, url, overlay, formData, $append;

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/functions/manage/products/add';
        formData = new FormData(this);

        $.ajax({

            url: url,
            data: formData,
            method: "POST",
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
                Overlay.close(overlay.overlay.parent());
            }

        });

        return false;
    })

    // add > change gallery ~ works
    .on('click', '[data-action="manage:products,add,addImage,gallery"] .item, [data-action="manage:products,edit,addImage,gallery"] .item', function(){

        let $t, $r, $c, $h, $e, url;

        $t = $(this);
        $r = $('[data-react="uploadFiles:upload-new-files"] input[name="gallery"]');
        $c = $t.closest('.product-overview');
        $h = $('[data-react="manage:products,add,addImage,gallery,info"]');
        
        if(!$t.hasClass('add-new')) {
            
            url = $t.data('json')[0].id;
        
            $c.find('.item').each(function() {
                $e = $(this);
                $e.removeClass('gal');
            });

            $t.addClass('gal');
            $h.css({ opacity:'0', 'visibility':'hidden', bottom:'-42px' });
            $r.val(url);
            
        }
        
    })
    
    // edit ~ works
    .on('click', '[data-action="manage:products,edit"]', function(){

        let overlay, url, formData;

        // add new overlay
        overlay = Overlay.add($body, true);

        // set url for xhr request
        url = dynamicHost + '/_magic_/ajax/content/manage/products/edit';
        formData = new FormData();
        formData.append("id", $(this).data("json")[0].id);

        $.ajax({
            
            url: url,
            data: formData,
            method: 'POST',
            type: 'HTML',
            contentType: false,
            processData: false,
            success: function(data,) {

                if(data !== 0) {
                    overlay.loader.remove();
                    overlay.overlay.append(data);
                } else {
                    responser("BRRRRRA! Bruder wieso? Wieso, wieso, wieso?");
                }
            }
        });
    })

    // edit > upload images ~ works partially
    .on('change', '[data-form="uploadFiles:products,add"]', function(){

        let $t, $append, overlay, uri, react;

        $t = $(this),
        uri = dynamicHost + '/_magic_/ajax/functions/manage/products/upload-images';

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        // start new fileupload (jquery)
        if($t.length > 0) {

            react = '[data-react="manage:products,add,addImage,show"]';
            store = '[data-react="uploadFiles:upload-new-files"] input[name="store"]';
            info = $('[data-react="manage:products,add,addImage,gallery,info"]');

            uploadProductImages(uri, $(this).find("input[type='file']"), react, overlay.overlay.parent(), store);

            info.css({
                opacity:"1",
                visibility:"visible",
                bottom:"-24px"
            });
        }

    })

    // edit > save ~ works
    .on('submit', '[data-form="manage:products,edit"]', function(e){

        e.preventDefault();

        let $t, url, overlay, formData, $append;

        // get current overlay
        $append = $body.find("page-overlay").find("content-card");

        // add new overlay
        overlay = Overlay.add($append, true, true);
        
        $t = $(this);
        url = dynamicHost + '/_magic_/ajax/functions/manage/products/edit';
        formData = new FormData(this);

        $.ajax({

            url: url,
            data: formData,
            method: "POST",
            type: 'JSON',
            contentType: false,
            processData: false,
            success: function(data){
                
                console.log(data);

                if(data.status) {

                    setTimeout(function(){
                        window.location.replace(window.location);
                    }, 600);
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

        return false;
    })

    // toggle ~ works
    .on("click", "[data-action='manage:products,toggle']", function() {

        let $t, id, url, react, contentCard, buttonIcon, buttonText;

        $t = $(this);
        id = $t.closest("content-card").data("json")[0].id;
        url = dynamicHost + "/_magic_/ajax/functions/manage/products/toggle";
        $contentCard = $t.closest("content-card");
        $react = $contentCard.find('[data-react="manage:products,toggle"]');

        $.ajax({

            url: url,
            data: { id: id },
            method: "POST",
            type: "JSON",
            success: function(data) {

                if(data.status) {
                    $react.toggleClass("enabled disabled");
                    $t.toggleClass("activate deactivate");
                }

            },
            error: function(data) {
                console.error(data);
            }

        });

    })

    // toggle archive ~ works
    .on("click", "[data-action='manage:products,delete']", function(e) {

        let $t, overlay;

        $t = $(this);
        $contentCard = $t.closest("content-card");
        $overlay = $contentCard.find("[data-react='manage:products,delete']");

        $overlay.addClass("visible");

    })

    // confirm archive ~ works
    .on("click", "[data-action='manage:products,delete,confirm']", function(e) {

        let $t, $overlay, url, id, $contentCard;

        $t = $(this);
        $contentCard = $t.closest("content-card");
        id = $contentCard.data("json")[0].id;
        url = dynamicHost + "/_magic_/ajax/functions/manage/products/delete";

        $.ajax({

            url: url,
            data: {
                id: id
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {

                if (data.status) {
                    $contentCard.addClass("tran-all-cubic").css({
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

    });

    function responser(text) {
        return showDialer(text, "casino", "Produkte");
    }
});

// actual uploading function ~ works partially
// TODO: Pictures wont upload on first try. Upload will first start after choosing a second picture
// ! which is not the first picture that had been chosen
// ? may I have to preload jQuery Image Upload
function uploadProductImages(uri, input, react, overlay = null, store = null) {

    let progress, formData, error, id, name, setValue, value;

    function closeOverlay(overlay) {
        Overlay.close(overlay);
    }

    input.fileupload({

        url: uri,
        context: this,
        autoUpload: false,
        add: function(e, data) {

            data.submit();
        },
        progressall: function(e, data) {

            // clear dialer timeout, to show through whole process
            clearTimeout(dialerTimeout);
            progress = "Hochgeladen: " + parseInt(data.loaded / data.total * 100, 10) + " %";

            // stay responsive
            showDialer(progress, "casino", "Produkte");
        },
        done: function(e, data) {

            store = $(store);

            url = dynamicHost + "/_magic_/ajax/elements/products/image";
            id = data.result.fileId;
            name = data.result.fileName;
            formData = new FormData();
            formData.append("id", id);
            formData.append("url", name);

            if(data.result.status) {

                // if store is set to true, store ids of uploaded images in store input field
                if(store !== null) {
                    
                    if(store.val().length < 1) {

                        setValue = store.val(store.val() + name);
                    } else {
                        
                        setValue = store.val(store.val() + "," + name);
                    }
                }

                $.ajax({

                    url: url,
                    data: formData,
                    method: "POST",
                    dataType: "HTML",
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        if(data !== 0) {

                            // prepend data from ajax call to show nely uploaded image
                            // and give good responsive web design
                            $(react).prepend(data);

                        }
                    },
                    error: function(data) {
                        console.error(data);
                    }
                });
            } else {
                showDialer(data._response.result.message, "casino", "Produkte");
            }
        },
        stop: function(e, data) {

            // close the overlay, so product details can be edited
            if(overlay !== null) {
                closeOverlay(overlay);
            }

            // reset file input value, so no files will be commited to the
            // product adding script
            this.value = "";
        },
        fail: function(e, data) {

            showDialer("Ein oder mehrere Bilder wurden nicht hochgeladen", "casino", "Produkte");
        }
    });

    return false;
}