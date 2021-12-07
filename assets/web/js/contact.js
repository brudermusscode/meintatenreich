$(function() {

    let $doc = $(document);

    $doc.delegate('[data-action="contact:send"]', 'click', function() {

        let $t, $f, dataString, url, empty;

        $t = $(this);
        $f = $t.closest('[data-form="contact"]');
        dataString = $f.serialize();
        url = dynamicHost + '/ajax/functions/contact';

        $f.find('input, textarea').each(function() {
            let $t = $(this);
            if ($.trim($t.val()).length < 1) {
                empty = true;
            } else {
                empty = false;
            }
        });

        if (!empty) {

            showDialer('Nachricht wird gesendet...');

            $.ajax({
                url: url,
                data: dataString,
                method: 'POST',
                type: 'JSON',
                success: function(data) {

                    console.log(data);

                    if(data.status) {
                        $f.trigger('reset');
                    }

                    showDialer(data.message);
                },
                error: function(data) {
                    console.error(data);
                }

            });

        } else {

            showDialer('Bitte fülle alle Felder aus');
        }
    });
});