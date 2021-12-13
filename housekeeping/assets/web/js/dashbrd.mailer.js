$(function() {

    let $body = $("body"), url, $t, $loader, $react, formData;

    // choose mailer option
    $(document).on('click', '[data-action="func:mailer,choose"] datalist ul li', function(){

        $t = $(this);
        $loader = $body.find('color-loader');
        $react = $body.find('[data-react="func:mailer,choose"]');

        let id = $t.data('json')[0].mail;
        url = dynamicHost + '/_magic_/ajax/content/functions/mailer';
        formData = { id: id };

        $react.empty();
        $loader.show();

        $.ajax({
            url: url,
            data: formData,
            method: 'POST',
            type: 'HTML',
            success: function(data){

                if(data !== 0) {

                    $react.append(data);
                    $loader.hide();
                } else {

                    responser("Etwas ist schief gelaufen");
                }


            }
        });


    });   
    
    // send
    $(document).on('submit', '[data-form="functions:mailer,roundmail"]', function(){

        $t = $(this);
        let wh = $t.data('wh');
        url = dynamicHost + '/_magic_/ajax/functions/functions/mailer/roundmail';
        formData = new FormData(this);

        let $react = $body.find('[data-react="show:loader"]');
        
        $.ajax({
            url: url,
            data: formData,
            method: 'POST',
            type: 'JSON',
            processData: false,
            contentType: false,
            success: function(data){

                console.log(data);
                
                if(data.status) {
                    setTimeout(function(){
                        //window.location.replace(window.location);
                    }, 1000);
                }
                
                responser(data.message);
            },
            error: function(data) {
                console.error(data);
            }
        });
    });

    function responser(text) {
        return showDialer(text, "mark_as_unread", "Mailer");
    }
})