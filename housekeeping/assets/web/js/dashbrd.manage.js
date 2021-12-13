class Manage {

    static loadPage(url = false, order = false, react = false, loader = false) {

        if(url) {
    
            // if there's no order given for filtering,
            // just show the default page content
            if(!order) {
                order = "all";
            }

            // clear content before adding the filter
            react.empty();

            // show loader if true
            if(loader) {
                loader.show();
            }
    
            // start the ajax call for getting content
            $.ajax({
    
                url: url,
                data: { order: order },
                method: 'POST',
                type: "HTML",
                success: function(data){

                    // if we successfully got content from the other
                    // PHP file, ...
                    if(data !== 0) {

                        // ... hide the loader and ...
                        if(loader) {
                            loader.hide();
                        }

                        // prepend the data received
                        react.prepend(data);
                    } else {
                        showDialer("WrongoOo");
                    }
                },
                error: function(data){
                    console.error(data);
                }
            });
        }
    }

    static loadMessages(url = false, order = false, react = false, loader = false) {

        if(url) {

            // if there's no order given for filtering,
            // just show the default page content
            if(!order) {
                order = "got";
            }

            // clear content before adding the filter
            react.empty();

            // show loader if true
            if(loader) {
                loader.show();
            }

            // start the ajax call for getting content
            $.ajax({
    
                url: url,
                data: { order: order },
                method: 'POST',
                type: "HTML",
                success: function(data){

                    console.log(data);

                    // if we successfully got content from the other
                    // PHP file, ...
                    if(data !== 0) {

                        // ... hide the loader and ...
                        if(loader) {
                            loader.hide();
                        }

                        // prepend the data received
                        react.prepend(data);

                        // remove loading class to enable tab change again
                        loader.closest("body").removeClass("loading");
                    } else {
                        showDialer("WrongoOo");
                    }
                },
                error: function(data){
                    console.error(data);
                }
            });
        }
    }
}

$(function() {

    let $doc, $bod, $body;

    $doc = $(document);
    $bod = $('body');
    $body = $('body');

    // filter index page by specific order ~ works
    $(document).on('click', '[data-action="manage:filter"] datalist ul li', function(){

        let $t, manage, react, loader, url, order;

        $t = $(this);
        manage = $t.closest('div[data-page]').data('page');
        react = $body.find('div[data-react="manage:filter"]');
        loader = $body.find('color-loader');
        order = $t.data('json')[0].order;

        switch(manage) {

            case "index":
                url = dynamicHost + '/_magic_/ajax/content/filter/index';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'orders':
                url = dynamicHost + '/_magic_/ajax/content/manage/filter/orders';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'products':
                url = dynamicHost + '/_magic_/ajax/content/manage/filter/products';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'customers':
                url = dynamicHost + '/_magic_/ajax/content/manage/filter/customers';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'courses':
                url = dynamicHost + '/_magic_/ajax/content/manage/filter/courses';
                Manage.loadPage(url, order, react, loader);
                break;

            case 'overview':

                switch(order) {

                    case "orders":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/orders";
                        break;

                    case "customers":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/customers";
                        break;

                    case "ratings":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/ratings";
                        break;

                    case "#nofilter":
                        url = dynamicHost + "/_magic_/ajax/content/overview/filter/all";
                        break;

                    default:
                        url = false;
                        break;
                }
                Manage.loadPage(url, order, react, loader);
                break;

            default:
                url = false;
                break;
        }
    });
});