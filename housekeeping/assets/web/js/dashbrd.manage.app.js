$(function(){

    $(document).on('click', '[data-action="manage:app,display"]', function(){

        let $t, $f, formData, method, type;

        $t = $(this),
        $f = $(document).find('#data-form-manage-app-display'),
        formData = $f.serialize(),
        method = "POST",
        type = "JSON";

        showDialer('Speichere...');
        
        let ajax = $.ajax({

            url: '/_magic_/ajax/functions/manage/app',
            data: formData,
            method: method,
            type: type,
            success: function(data){

                showDialer(data.message);

            },
            error: function(data) {
                console.error(data);
            }

        });

    });

});