$(function() {

    // DRY code responsive overlay

    let body = $("body");
    let $body = $("body");

    // change image ~ works
    $(document).on('click', '[data-action="change-product-image"] li', function() {

        let $t, url, react;

        $t = $(this);
        url = $t.data('json')[0].url;
        react = $body .find('[data-react="productview:gallery,change"]');

        // remove selected circle from current one and...
        $t.parent().find("li").removeClass("selected");

        // ... set new selected image
        $t.addClass('selected');

        // find and remove current large gallery image
        react.find("img").remove();
        react.data("url", url).append('<img src="' + url + '" class="tran-all almid-h">');

        setTimeout(function(){
            fadeImages(react.find("img"));
        }, 200);
        


    })

    // show full image ~ works
    .on('click', '[data-action="open-image-viewer"]', function() {
        
        let url;

        url = $(this).data('url');

        // add responsive overlay
        addOverlay(body, dark = true);
        let overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        let loader = overlay.find('loader').parent();
        
        $.ajax({
            
            url: dynamicHost + "/ajax/content/productview/resize-image",
            data: { url: url },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                loader.remove();
                overlay.append(data);
                overlay.find('image-viewer img').css({ "visibility":"visible", 'opacity':'1' });
                    
            }
            
        });
        
    })

    // open description ~ works
    .on('click', '[data-action="open-desc"]', function() {

        let id = $(this).data('json')[0].id;
        let url = dynamicHost + "/ajax/content/productview/description";
        let action = 'open-desc';

        // add responsive overlay
        addOverlay(body);
        let overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        let loader = $('loader').parent();
        
        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
            
                loader.remove();
                overlay.append(data);
            
            }
            
        });
        
    })

    // add to shopping card ~ works
    .on('click', '[data-action="add-scard"]', function() {
        
        let t, id, action, res, url, $shoppingCardAmount;

        t = $(this),
        id = t.data('json')[0].id,
        action = 'add-scard',
        res,
        url = dynamicHost + "/ajax/functions/productview/add-shoppingcard",
        $shoppingCardAmount = $('[data-react="add-scard"] p');
        
        t.attr('disabled', 'disabled');
        t.removeAttr('data-action');
        
        $.ajax({
            
            data: { action: action, id: id },
            url: url,
            method: 'POST',
            type: 'JSON',
            success: function(data) {

                let status, message, shoppingCardAmount;

                status = data.status,
                code = data.code,
                message = data.message,
                shoppingCardAmount = data.shoppingCardAmount;

                if(status) {

                    $shoppingCardAmount.html(shoppingCardAmount + 1);

                    res = 'Produkt wurde für 6 Stunden reserviert.';
                    t.find('p:nth-of-type(2)').html('Im Warenkorb');
                    t.find('p:first-of-type i').attr('class', 'icon-ok');
                }
                
                showDialer(message);
                
            },
            error: function(data) {
                console.log(data);
            }
            
        });
        
    })

    // add to favorite ~ works
    .on('click', '[data-action="add-scard-remember"]', function() {
        
        let t = $(this);
        let id = t.parent().data('json')[0].id;
        let action = 'add-scard-remember';
        let res;
        let url = dynamicHost + "/ajax/functions/productview/add-favorite";

        showDialer('Speichere...');
        
        $.ajax({
            
            url: url,
            data: { action: action, id: id },
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                switch(data) {
                    case '0':
                        res = 'Bitte logge Dich ein, um forzufahren!';
                        break;
                    case '1':
                        res = 'Produkt gemerkt!'
                        t.toggleClass('white brown');
                        t.find('p:nth-of-type(2)').html('Gemerkt');
                        t.find('p:first-of-type i').attr('class', 'icon-star-filled');
                        break;
                    case '2':
                        t.toggleClass('white brown');
                        res = 'Produkt von der Merkliste entfernt!'
                        t.find('p:nth-of-type(2)').html('Merken');
                        t.find('p:first-of-type i').attr('class', 'icon-star-empty');
                        break;
                    default:
                        res = 'Ein unbekannter Fehler ist aufgetreten.';
                        break;
                }
                
                showDialer(res);
                
            }
            
        });
        
    })

    // add rating ~ works
    .on('click', '[data-action="add-rating"]', function() {
        
        let overlay, id, url;

        id = $(this).data('json')[0].id;
        url = dynamicHost + "/ajax/content/productview/rate",
        
        $.ajax({
            
            data: { id: id },
            url: url,
            method: 'POST',
            type: 'HTML',
            success: function(data) {

                if(data == '3') {
                    error = "Produkte können erst nach Kauf bewertet werden";
                } else if(data == '2') {
                    error = "Das Produkt scheint nicht mehr zu existieren";
                } else if(data == '1') {
                    error = "Bitte logge dich ein, um Bewertungen abzugeben";
                } else {
                    addOverlay($body);
                    overlay = body.find('page-overlay');
                    overlay.append(data);
                }

                if(data == '1' || data !== '2' || data !== '1') {
                    showDialer(error);
                }
                
            },
            error: function(data) {
                console.error(data);
            }
            
        });
    })

    // add rating >> activate send button ~ works
    .on('keyup', '[data-action="po-comment"]', function(){
            
        let val = $.trim($(this).val().length);
        let sendButton = $(this).closest("form").find('button[type="submit"]');

        if(val >= 3) {
            sendButton.addClass('active');
        } else {
            sendButton.removeClass('active');
        }

    })

    // submit rating
    .on('submit', '[data-form="productview:rating"]', function(e){

        e.preventDefault();

        let $t, textarea, textareaLength, inputLength, formData, $overlay, $react, $submitButton, $wideContainer;

        $t = $(this);
        textarea = $t.closest('.textarea').find('textarea');
        textareaLength = $.trim(textarea.val().length);
        formData = new FormData(this);
        inputLength = $t.find('input[name="rate"]').val();
        $overlay = $('page-overlay');
        $react = $body.find('[data-react="productview:ratings,add"]');
        $wideContainer = $overlay.find('wide-container');
        $submitButton = $('[data-action="add-rating"]');
        url = dynamicHost + "/ajax/functions/productview/rate";
        
        if(textareaLength < 3) {
            
            showDialer("Bitte gib eine Bewertung ein");
        } else if($.trim(inputLength).length < 1 && !($.isNumeric(inputLength))) {

            showDialer("Wähle zwischen 1 und 5 Sternen");
        } else {
        
            let wcOverlay, closeover, wcLoader;

            // add overlay to prevent clicking
            addOverlay($wideContainer, dark = true);
            wcOverlay = $wideContainer.find('page-overlay');
            closeover = wcOverlay.find('close-overlay').remove();
            addLoader(wcOverlay, 'floating');
            wcLoader = wcOverlay.find('loader').parent();

            $.ajax({

                url: url,
                data: formData,
                method: 'POST',
                type: 'JSON',
                contentType: false,
                processData: false,
                success: function(data) {
                    
                    console.log(data);

                    url = dynamicHost + "/ajax/content/elements/rate";

                    if(data.status) {
                        
                        $.ajax({
                            
                            data: { comment: textarea.val(), rate: inputLength },
                            url: url,
                            method: 'POST',
                            type: 'HTML',
                            success: function(data) {
                                
                                if(data !== 0) {
                                    $react.append(data);
                                }

                                $overlay.removeAttr('style');

                                setTimeout(function(){
                                    $overlay.remove();
                                }, 400);
                                
                            }
                        });
                    }

                    showDialer(data.message);
                },
                error: function(data) {
                    console.error(data);
                }
            });
        }
    })

    // Comment voting
    .on('click', '[data-action="comment-vote"] .button', function() {

        let t = $(this);
        let vote = t.data('json')[0].vote;
        let cid = t.parent().data('json')[0].cid;
        let pid = t.parent().data('json')[0].pid;
        let uid = t.parent().data('json')[0].uid;
        let action = 'comment-vote';
        let res;
        let amt = parseInt(t.parent().find('.up p:nth-of-type(2)').text());
        
        $.ajax({

            data: { action: action, vote: vote, cid: cid, pid: pid, uid: uid },
            url: '/ajax/commentvote',
            method: 'POST',
            type: 'TEXT',
            success: function(data) {
                
                switch(data) {
                    case '':
                        res = 'Bitte logge Dich ein, um fortzufahren.';
                        break;
                    case '0':
                        res = 'Ein unbekannter Fehler ist aufgetreten.';
                        break;
                    case '1':
                        res = 'Der Kommentar existiert nicht!';
                        break;
                    case 'up':
                        if(!(t.parent().find('.up').hasClass('white'))) {
                            t.parent().find('.up p:nth-of-type(2)').html(amt + 1);
                        }
                        t.toggleClass('blue white');
                        t.parent().find('.down').addClass('blue').removeClass('white');
                        res = 'Votum abgegeben!';
                        break;
                    case 'down':
                        if(t.parent().find('.up').hasClass('white')) {
                            t.parent().find('.up p:nth-of-type(2)').html(amt - 1);
                        }
                        t.toggleClass('blue white');
                        t.parent().find('.up').addClass('blue').removeClass('white');
                        res = 'Votum abgegeben!';
                        
                        break;
                    case 'inactive':
                        if(t.parent().find('.up').hasClass('white')) {
                            t.parent().find('.up p:nth-of-type(2)').html(amt - 1);
                        }
                        t.parent().find('.button').addClass('blue').removeClass('white');
                        res = 'Votum entfernt!';
                        break;
                }

                showDialer(res);

            }

        });

    });

});