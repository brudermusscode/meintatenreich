<?php

    require_once "../mysql/_.session.php";

    $ptit = 'Intern: SEPA-Lastschrift';
    $pid = "intern:sepa";

    include_once "./assets/templates/head.php";
    include_once "./assets/templates/header.php";

?>
       
        <div class="main-content">
            <div class="inr">
           
                <h2 class="mb24">SEPA-Lastschriftverfahren</h2>
                
                <div class="rd4 p12" style="background:#dae5ff;">
                    <p>SEPA (Single Euro Payments Area) ist ein Projekt zur Vereinheitlichung von bargeldlosen Zahlungen innerhalb des EURO-Raums.</p>
                </div>
                
                <p class="fw7 mt24 mb8">Allgemeine Regelung der SEPA-Lastschrift</p>
                <p class="mb12">Durch die Einführung des SEPA-Lastschriftverfahrens wurde die Bankleitzahl und die Kontonummer durch die IBAN und die BIC abgelöst. Die bisherige Lastschrifteinzugsermächtigung erhielt eine neue Bezeichnung und heißt nun "SEPA-Lastschriftmandat".</p>
                <p class="mb12">Indem Sie MeinTatenreich Ihre Bankverbindung mitteilen, ermächtigen Sie unser Unternehmen, Zahlungen von Ihrem Bankkonto mittels Lastschrift einzuziehen. Zugleich weisen Sie Ihr Kreditinstitut an, die von MeinTatenreich auf Ihr Konto gezogenen Lastschriften einzulösen. Im Rahmen der mit Ihrem Kreditinstitut vereinbarten Bedingungen können Sie innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen.</p>
                
                <p class="fw7 mt24 mb8">Generierung eines SEPA-Lastschriftmandat</p>
                <p class="mb12">Ein SEPA-Lastschriftmandat wird automatisch mit dem hinzufügen einer neuen Bankverbindung unter <strong class="fw6">"Mein Konto > Zahlungsarten"</strong> generiert. Bankverbindungen können ebenfalls direkt über den Bestellvorgang hinzugefügt werden.</p>
                
                <p class="fw7 mt24 mb8">Einsicht in Ihr SEPA-Lastschriftmandat</p>
                <p class="mb12">Das für Ihre Bankverbindung gültige SEPA-Lastschriftmandat kann unter <strong class="fw6">"Mein Konto > Zahlungsarten"</strong> und einen Klick auf die Mandat-Indentifikationsnummer durch die Bearbeitung der jeweiligen Bankverbindung eingesehen werden.</p>
                
                <p class="fw7 mt24 mb8">Änderung zugehöriger Bank- und Adressdaten</p>
                <p class="mb12">Jeweilige Kontodaten und die Adresse zugehörig zum SEPA-Lastschriftmandat können unter <strong class="fw6">"Mein Konto > Zahlungsarten/Adressen"</strong> bearbeitet werden. Die Adresse ist immer die, die als Präferenz für Bestellungen durch die Bearbeitung der jeweiligen Adresse eingestellt wurde bzw. die zuletzt Hinzugefügte. Hier können ebenfalls <strong class="fw6">neue Bankverbindungen und Lieferadressen</strong> hinzugefügt werden.</p>
                
                <p class="fw7 mt24 mb8">Stornierung Ihres SEPA-Lastschriftmandats</p>
                <p class="mb24">Um ein SEPA-Lastschriftmandat zu stornieren, können Sie unter <strong class="fw6">"Mein Konto > Zahlungsarten"</strong> die zugehörige Bankverbindung durch die Bearbeitung dieser löschen. Hierbei ist zu beachten, dass sich das Löschen einer Bankverbindung nicht auf bereits aufgegebene Bestellungen unter der Verwendung dieser auswirkt.</p>
           
                <p class="fw7 mb8">Auswirkung von Änderungen an SEPA-Lastschriftmandat und zugehörigen Adressdaten</p>
                <div class="mb12" style="border-left:3px solid #ffbdbd;padding-left:12px;">
                    <p class="fw6 mb8">Änderungen an Bankverbindungen</p>
                    <p class="mb12">Änderungen von Bankverbindungen haben keine Auswirkung auf bereits aufgegebene Bestellungen.</p>
                </div>
                
                <div class="mb12" style="border-left:3px solid #ffbdbd;padding-left:12px;">
                    <p class="fw6 mb8">Änderungen an Adressen</p>
                    <p class="mb12">Sofern Bestellungen nicht von MeinTatenreich als "Versandt" gekennzeichnet sind, können Änderungen an Lieferadressen weiterhin Auswirkung haben.</p>
                </div>
           
            </div>
        </div>

    </body>
</html>