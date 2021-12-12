 $(function() {
 
    // boolean checkbox
    $(document).delegate('[data-element="boolean"] .bool', 'click', function(){

        let $t = $(this);

        $t.parent().children().removeClass('active');
        $t.addClass('active');

    })

    .on('click', '[data-element="boolean-great"]', function(){

        let $t = $(this);
        let $i = $t.find('input[type="hidden"]');

        if($t.hasClass('on')) {
            $i.val('0');
            $t.removeClass('on');
        } else {
            $i.val('1');
            $t.addClass('on');
        }

    })

    // chooser element
    .on("click", '[data-element="chooser"] ul li', function(){

        let $t = $(this);
        let $c = $t.closest('[data-element="chooser"]');

        $c.find('ul li').each(function(elem){
            let $e = $(this);
            $e.removeClass('active');
        });

        $t.addClass('active');

    })

    // TOOLTIP
    .on('mouseenter', '[data-tooltip]', function () {

        var $t = $(this);
        var $text = $t.data('tooltip');
        var th = $t.height();
        var tw = $t.width();
        var al = $t.data('tooltip-align');

        if (!$t.hasClass('tooltip-active')) {
            $t.append('<tooltip class="dark tran-all-cubic"><tt-inr>' + $text + '</tt-inr></tooltip>');
            var $tt = $t.find('tooltip');
            if (al === 'bottom') {
                $tt.css({
                    'top': 'calc(' + th + 'px)'
                }).addClass('almid-h');
            } else if (al === 'left') {
                $tt.css({
                    'right': 'calc(' + tw + 'px)'
                }).addClass('almid-w');
            } else if (al === 'right') {
                $tt.css({
                    'left': 'calc(' + tw + 'px)'
                }).addClass('almid-w');
            } else {
                $tt.css({
                    'bottom': 'calc(' + th + 'px)'
                }).addClass('almid-h');
            }
            var show = setTimeout(function () {

                if (al === 'bottom') {
                    $tt.css({
                        'opacity': '1',
                        'top': 'calc(' + th + 'px + 6px)'
                    });
                } else if (al === 'left') {
                    $tt.css({
                        'opacity': '1',
                        'right': 'calc(' + tw + 'px + 6px)'
                    });
                } else if (al === 'right') {
                    $tt.css({
                        'opacity': '1',
                        'left': 'calc(' + tw + 'px + 6px)'
                    });
                } else {
                    $tt.css({
                        'opacity': '1',
                        'bottom': 'calc(' + th + 'px + 6px)'
                    });
                }

                $t.addClass('tooltip-active');
            }, 1);
        }

    }).on('mouseleave', '[data-tooltip]', function () {

        var $t = $(this);
        var $tt = $t.find('tooltip');

        if ($t.hasClass('tooltip-active')) {
            $tt.css('opacity', '0');

            var hide = setTimeout(function () {
                $tt.remove();
                $t.removeClass('tooltip-active');
            }, 100);

        }

    })

    // chooser element > open ~ manage
    .on('click', '[data-element="admin-select"]', function () {

        let $t, $dl, dlh, dlw, seh, lal, wid, $contentCard;

        $t = $(this);
        $contentCard = $t.closest("content-card");
        $dl = $t.find('datalist');
        dlh = $dl.find('ul').height();
        dlw = $dl.find('ul').width();
        seh = $t.height();

        lal = $t.data('list-align');
        wid = $t.data('list-size');

        if (!$t.hasClass('open')) {

            $t.addClass('open');
            $dl.css({
                'top': seh + 'px',
                'opacity': '1',
                'width': wid + 'px',
                'height': 'calc(' + dlh + 'px + 24px)',
                'border-radius': '4px'
            });
        }
    })

    // chooser element > click ~ manage
    .on('click', 'datalist ul li', function () {

        var $t = $(this);
        var $el = $t.closest('[data-element="admin-select"]');
        var $dl = $t.closest('datalist');
        var $ch = $el.find('.text');
        var ch = $t.html();

        if ($el.hasClass('open')) {
            $ch.text(ch);
            setTimeout(function () {
                $el.removeClass('open');
            }, 400);
        }
    
        let attr = $el.attr('data-input');
        if(typeof attr !== typeof undefined && attr !== false) {
            let id = $t.data('json')[0].id;
            let ip = $dl.find('input[type="hidden"]').val(id);
        }

    });


});