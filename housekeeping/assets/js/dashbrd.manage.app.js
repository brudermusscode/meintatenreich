$(function(){

    $(document).on('click', '[data-action="manage:app,display"]', function(){

        let $t = $(this);
        let $f = $bod.find('#data-form-manage-app-display');
        let dS = $f.serialize();

        showDialer('Speichere...');
        
        let ajax = $.ajax({

            url: '/hk/ajax/manage/app',
            data: dS,
            method: 'POST',
            type: 'TEXT',
            success: function(data){

                if(data === 'success') {
                    showDialer('Gespeichert!');
                } else {
                    showDialer('Ein Fehler ist aufgetreten...');
                }

            }

        });

    });

});