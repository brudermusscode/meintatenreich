$(function() {

    let $doc = $(document);

    $doc.delegate('[data-action="contact:send"]', 'click', function() {

        let $t = $(this);
        let $f = $t.closest('[data-form="contact"]');
        let dataString = $f.serialize();
        let url = dynamicHost + '/ajax/functions/contact';
        let empty;
        let res;

        $f.find('input, textarea').each(function() {
            let $t = $(this);
            if ($.trim($t.val()).length < 1) {
                empty = true;
            } else {
                empty = false;
            }
        });

        if (empty === false) {

            showDialer('Nachricht wird gesendet...');

            let ajax = $.ajax({
                url: url,
                data: dataString,
                method: 'POST',
                type: 'TEXT',
                success: function(data) {

                    console.log(data);

                    switch (data) {
                        case '':
                        case '0':
                        default:
                            res = 'Oh! Ein Fehler!';
                            break;
                        case '1':
                            res = 'Diese Kategorie existiert nicht';
                            break;
                        case '2':
                            res = 'Dein Vorname enthält ungültige Zeichen';
                            break;
                        case '3':
                            res = 'Dein Nachname enthält ungültige Zeichen';
                            break;
                        case '4':
                            res = 'Die Form deiner E-Mail Adresse ist ungültig. Nutze <strong class="fw7">name@host.endung</strong>';
                            break;
                        case 'success':
                            res = 'Deine Nachricht wurde erfolgreich versendet! Du solltest bereits eine Bestätigung erhalten haben';
                            $f.trigger('reset');
                    }

                    showDialer(res);
                }

            });

        } else {

            showDialer('Bitte fülle alle Felder aus');
        }

    });

});