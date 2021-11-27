<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

$ptit = "Zahlungsarten - SEPA Lastschriftmandat";
$pid = "payments";
$rgname = 'SEPA';

if (!$loggedIn) {
    header('location: ../oops');
}

if (isset($_GET['pmid'])) {

    $pmid = htmlspecialchars($_GET['pmid']);
    $uid = $my->id;

    // check if billing method belongs to user/exists
    $getBilling = $pdo->prepare("
        SELECT *, customer_billings.id AS bid, customer_billings_sepa.id AS bsid 
        FROM customer_billings, customer_billings_sepa 
        WHERE customer_billings.id = customer_billings_sepa.pmid 
        AND customer_billings.id = ? 
        AND customer_billings.uid = ?
    ");
    $getBilling->execute([$pmid, $uid]);

    if ($getBilling->rowCount() > 0) {

        $b = $getBilling->fetch();

        if (!$my->addressPreference) {

            $address = 'Keine Adresse hinzugefügt';
        }
    } else {

        //header('location: ../oops');
    }
} else {

    //header('location: ../oops');
}

include_once $sroot . "/assets/templates/global/head.php";
include_once $sroot . "/assets/templates/global/header.php";

?>

<div id="main">
    <div class="outer">
        <div class="inr">


            <?php include_once $sroot . "/assets/templates/customers/menu.php"; ?>

            <div class="main-overflow-scroll rt ph42">

                <div>
                    <p class="fw7 mb12" style="font-size:1.4em;">SEPA Basis-Lastschriftmandat</p>

                    <div class="mb12">
                        <p class="fw6">Gläubiger-Identifikationsnummer</p>
                        <p>DE53553110115021974</p>
                    </div>
                    <div>
                        <p class="fw6">Mandats-Identifikationsnummer</p>
                        <p><?php echo $b->mid; ?></p>
                    </div>

                    <div class="mt24">
                        <p class="mb8">Ich/wir ermächtige/n MeinTatenreich, Zahlungen von meinem/unserem Konto mittels Lastschrift einzuziehen. Zugleich weise/n ich/wir mein/unser Kreditinstitut an, die von MeinTatenreich auf mein/unser Konto gezogenen Lastschriften einzulösen.</p>
                        <p class="mb24">Hinweis: Ich/wir kann/können innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem/unserem Kreditinstitut vereinbarten Bedingungen.</p>
                    </div>

                    <div class="mb12">
                        <p class="fw6">Vorname und Nachname (Kontoinhaber)</p>
                        <p><?php echo $b->account; ?></p>
                    </div>

                    <?php if (!$my->addressPreference) { ?>
                        <div class="mb12">
                            <p class="fw6">Adresse</p>
                            <p><?php echo $address; ?>. <a href="/my/addresses">Klicke hier, um eine Adresse hinzuzufügen.</a></p>
                        </div>
                    <?php } else { ?>
                        <div class="mb12">
                            <p class="fw6">Straße und Hausnummer</p>
                            <p><?php echo $ap->address; ?></p>
                        </div>

                        <div class="mb12">
                            <p class="fw6">Postleitzahl und Ort</p>
                            <p><?php echo $ap->postcode . ' ' . $ap->city; ?></p>
                        </div>
                    <?php } ?>


                    <div class="mb12">
                        <p class="fw6">Kreditinstitut (BIC)</p>
                        <p><?php echo '' . substr($b->bic, 0, 2) . '&bull;&bull;&bull;&bull;&bull;&bull;&bull;' . substr($b->bic, -2); ?></p>
                    </div>

                    <div class="mb12">
                        <p class="fw6">Internationale Bankkontonummer (IBAN)</p>
                        <p><?php echo 'Endet auf ' . substr($b->iban, -2); ?></p>
                    </div>

                    <div class="mb12">
                        <p class="fw6">Datum</p>
                        <p><?php echo $b->timestamp; ?></p>
                    </div>

                    <div class="mt24">
                        <p class="mb24">Hinweis: Meine/Unsere Rechte zu dem obigen Mandat sind in einem Merkblatt enthalten, das ich/wir von meinem/unserem Kreditinstitut erhalten kann/können.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?php include_once $sroot . "/assets/templates/global/footer.php"; ?>