$(function() {

    // DRY code responsive overlay

    let body = $("body");

    // change image
    $(document).on('click', '[data-action="change-product-image"] li', function() {

        let t = $(this);
        let tdata = t.data('json');
        let url = 'https://statics.meintatenreich.de/img/products/' + tdata[0].url;
        let galimg = $('[data-react="change-product-image"]');
        addLoader(galimg, 'floating');
        let loader = galimg.find('loader').parent();
        let galimgg = galimg.find('img');
        
        galimg.data('url', tdata[0].url);
        
        $('[data-action="change-product-image"] li').removeClass('selected');
        t.addClass('selected');
        
        
        galimgg.remove();
        galimg.append('<img src="'+url+'" class="tran-all almid-h">');
        setTimeout(function(){
            galimg.find('img').css({ 'visibility':'visible', 'opacity':'1' });
        }, 10);
        loader.remove();

    })

    // show full image
    .on('click', '[data-action="open-image-viewer"]', function() {
        
        let url = 'https://statics.meintatenreich.de/img/products/' + $(this).data('url');
        let action = 'open-image-viewer';

        // add responsive overlay
        addOverlay(body, dark = true);
        let overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        let loader = overlay.find('loader').parent();
        
        $.ajax({
            
            url: dynamicHost + "/ajax/content/productview/resize-image",
            data: { action: action, url: url },
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                loader.remove();
                overlay.append(data);
                overlay.find('image-viewer img').css({ "visibility":"visible", 'opacity':'1' });
                    
            }
            
        });
        
    })

    // open description
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

    // add to shopping card
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

    // add to favorite
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

    // add rating
    .on('click', '[data-action="add-rating"]', function() {
        
        addOverlay(body);
        let overlay = body.find('page-overlay');
        addLoader(overlay, 'floating');
        let loader = $('loader').parent();
        let action = 'add-rating';
        let id = $(this).data('json')[0].id;
        let res;
        
        $.ajax({
            
            data: { action: action, id: id },
            url: '/get/addrating',
            method: 'POST',
            type: 'HTML',
            success: function(data) {
                
                switch(data) {
                    case '':
                    case '0':
                    case '1':
                        res = 'Ein unbekannter Fehler ist aufgetreten.';
                        overlay.removeAttr('style');
                        showDialer(res);
                        setTimeout(function(){
                            overlay.remove();
                        }, 400);
                        break;
                    case '2':
                        res = 'Sie müssen das Produkt kaufen, bevor Sie es bewerten können!';
                        overlay.removeAttr('style');
                        showDialer(res);
                        setTimeout(function(){
                            overlay.remove();
                        }, 400);
                        break;
                    default:
                        loader.remove();
                        overlay.append(data);
                }
                
            }
            
        });
        
    })

    .on('keyup', '[data-action="po-comment"]', function(){
            
        let val = $.trim($(this).val().length);
        let sendButton = $('[data-react="po-comment"]');

        if(val >= 3) {
            sendButton.attr('data-action', 'submit-comment')
            .addClass('active');
        } else {
            sendButton.removeAttr('data-action')
            .removeClass('active');
        }

    })

    .on('click', '[data-action="submit-comment"]', function(){

        let textarea = $(this).closest('.textarea').find('textarea');
        let valLen = $.trim(textarea.val().length);
        let res;
        let form = $('[data-form="rating"]');
        let formData = form.serialize();
        let inputLen = form.find('input[name="rate"]').val();
        let overlay = $('page-overlay');
        let addRating = $('[data-react="my-rating"]');
        let comment = textarea.val();
        let action = 'get-my-rating';
        let wc = overlay.find('wide-container');
        let buttonHide = $('[data-action="add-rating"]');
        
        if(valLen < 3) {
            res = 'Bitte gebe mindestens 3 Zeichen ein!'
            showDialer(res);
        } else if($.trim(inputLen).length < 1 && !($.isNumeric(inputLen))) {
            res = 'Bitte bewerte das Produkt mit Sternen!'
            showDialer(res);
        } else {
        
            $.ajax({

                data: formData,
                url: '/ajax/submitcomment',
                method: 'POST',
                type: 'TEXT',
                success: function(data) {
                    
                    switch(data) {
                        default:
                        case '0':
                        case '1':
                        case '4':
                            res = 'Ein unbekannter Fehler ist aufgetreten.';
                            break;
                        case '2':
                            res = 'Sie müssen das Produkt kaufen, bevor Sie es bewerten können!';
                            break;
                        case '3':
                            res = 'Ihr Kommentar enthält ungültige Zeichen!';
                            break;
                        case '5':
                            res = 'Sie haben das Produkt bereits bewertet!';
                            break;
                        case '6':
                            res = 'Bewertung wird veröffentlicht...';
                            addOverlay(wc, dark = true);
                            let wcOverlay = wc.find('page-overlay');
                            let closeover = wcOverlay.find('close-overlay').remove();
                            addLoader(wcOverlay, 'floating');
                            let wcLoader = wcOverlay.find('loader').parent();
                            
                            $.ajax({
                                
                                data: { action: action, comment: comment, rate: inputLen },
                                url: '/get/myrating',
                                method: 'POST',
                                type: 'HTML',
                                success: function(data) {
                                    
                                    res = 'Ihre Bewertung wurde erfolgreich veröffentlicht!';
                                    buttonHide.remove();
                                    addRating.append(data);
                                    overlay.removeAttr('style');
                                    setTimeout(function(){
                                        overlay.remove();
                                    }, 400);

                                    showDialer(res);
                                    
                                }
                                
                            });

                    }

                    showDialer(res);

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