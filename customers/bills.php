<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

// DOMPDF
require_once $sroot . '/assets/templates/dompdf/autoload.inc.php';

// reference the Dompdf namespace
Dompdf\Autoloader::register();

use Dompdf\Dompdf;

if (isset($_GET["bid"])) {

    $bid = $shop->removeFileType($_GET["bid"]);
} else {

    header("location: /oops");
}

if (
    is_numeric($bid) &&
    $loggedIn
) {

    // get bill
    $getBill = $pdo->prepare("SELECT * FROM customer_buys_pdf WHERE id = ?");
    $getBill->execute([$bid]);

    // check if bill exists and user is owner
    if ($getBill->rowCount() > 0) {

        $b = $getBill->fetch();

        $getOrder = $pdo->prepare("SELECT * FROM customer_buys WHERE id = ?");
        $getOrder->execute([$bid]);

        // check if order exists
        if ($getOrder->rowCount() > 0) {

            // fetch order information
            $o = $getOrder->fetch();

            // check for permissions to see this bill
            // only admins and actual order source holder
            if ($my->admin == '1' || $o->uid == $my->id) {

                // get billing method information
                $getBilling = $pdo->prepare("SELECT * FROM customer_billings WHERE id = ? AND uid = ?");
                $getBilling->execute([$o->pmid, $my->id]);
                $billing = $getBilling->fetch();

                // get address information
                if ($my->admin === '1') {

                    $queryAttributes = [$o->adid, $o->uid];
                } else {

                    $queryAttributes = [$o->adid, $my->id];
                }

                $getAddress = $pdo->prepare("SELECT * FROM customer_addresses WHERE id = ? AND uid = ?");
                $getAddress->execute($queryAttributes);
                $a = $getAddress->fetch();
            } else {
                echo "no permissions"; // no permissions
            }
        } else {
            echo "not exist"; // order does not exist
        }
    } else {
        echo "not owner"; // not owner
    }
} else {
    echo "not logged in"; // not logged in
}





define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "DOMPDF_DIR" . " / tmp");

// instantiate and use the dompdf class
$dompdf = new Dompdf();

ob_start();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rechnung_<?php echo $o->orderid; ?> - PDF</title>

    <style type="text/css">
        body,
        html,
        *,
        div,
        p,
        span {
            font-family: sans-serif;
        }

        .end-price {
            font-weight: 600;
        }
    </style>
</head>

<body>

    <br>

    <table width="90%" align="center" style="font-size:14px;">

        <tr>

            <td style="width:124px;">
                <img height="100px" width="100px" src="<?php echo $url["img"]; ?>/global/pdf_logo.png" />
            </td>

            <td colspan="2">
                <span style="font-size:32px;font-weight:700;">MeinTatenreich</span><br>
                <span style="font-size:14px;">Bestellung</span>
                <span style="font-size:16px;font-weight:600;color:rgb(201, 151, 89);"> <?php echo $o->orderid; ?></span>
            </td>
        </tr>

    </table>

    <br><br>

    <table width="90%" align="center" style="font-size:14px;">

        <tr>
            <td>
                Versand an
            </td>
            <td align="right" colspan="3">
                Datum/Uhrzeit
            </td>
        </tr>

        <tr>
            <td>
                <strong><?php echo $a->fullname; ?></strong>
            </td>
            <td>
                <strong><?php echo $a->address; ?></strong>
            </td>
            <td>
                <strong><?php echo $a->postcode . ' ' . $a->city; ?></strong>
            </td>
            <td align="right">
                <strong>
                    <?php

                    $properts = date("d.m.Y, H:i:s", strtotime($o->timestamp));
                    echo $properts;
                    ?>
                </strong>
            </td>
        </tr>

    </table>

    <br><br>

    <table width="90%" align="center" style="font-size:14px;">

        <?php

        $getOrderProducts = $pdo->prepare("
            SELECT * FROM customer_buys_products, products 
            WHERE customer_buys_products.bid = ?
            AND customer_buys_products.pid = products.id
            ORDER BY customer_buys_products.id DESC
        ");
        $getOrderProducts->execute([$o->id]);
        $opCount = $getOrderProducts->rowCount();

        $pnum = 0;
        $pprice = 0;

        ?>

        <tr>
            <td colspan="3">
                <strong>Artikelübersicht (<?php echo $opCount; ?>)</strong>
                <br>
                <hr>
                <br>
            </td>
        </tr>

        <?php

        foreach ($getOrderProducts->fetchAll() as $op) {
            $pnum++;
            $pprice = $pprice + $op->price;

        ?>

            <tr>

                <td colspan="2">
                    <?php echo $pnum . '.) ' . $op->name; ?>
                </td>
                <td align="right">
                    <?php echo number_format($op->price, 2, ',', '.') . ' €'; ?>
                </td>

            </tr>

        <?php } ?>

        <tr>
            <td colspan="3">
                <br>
                <hr>
            </td>
        </tr>

        <tr>
            <td colspan="2" align="right">
                Artikel gesamt<br>
                Versand<br>
                Gesamtsumme der Bestellung
            </td>
            <td align="right" class="end-price">
                <?php echo number_format($pprice, 2, ',', '.') . ' €'; ?><br>
                <?php echo number_format($o->price_delivery, 2, ',', '.') . ' €'; ?><br>
                <?php echo number_format($pprice + $o->price_delivery, 2, ',', '.') . ' €'; ?>
            </td>
        </tr>

    </table>


</body>

</html>

<?php

// Clean up created content
$content = ob_get_clean();

// Setup for PDF file
$dompdf->loadHtml($content);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$options = array('Attachment' => 0);

// Vardump output
$pdf_gen = $dompdf->output();
$full_path = $_SERVER["DOCUMENT_ROOT"] . '/pdfbills/mtr_rechnung+' . $o->orderid . '.pdf';
$exist_path = 'pdfbills/';
$filename = 'mtr_rechnung+' . $o->orderid . '.pdf';

// Check file existence
if (!file_exists($exist_path . $filename)) {

    // Check file creation
    if (!file_put_contents($full_path, $pdf_gen)) {

        echo '

                <!DOCTYPE html>
                <html>

                    <head>
                        <meta charset="UTF-8">
                        <title>Oh oh!</title>

                        <style type="text/css">
                            body, html, *, div, p, span { font-family: sans-serif;font-size:100%;line-height:1.2;background-color:rgb(255, 233, 189); }
                            .end-price { font-weight:600; }
                        </style>
                    </head>

                    <body>

                        <p style="text-align:center;margin-top:48px;">
                            <img height="160px" src="' . $url["img"] . '/global/pdf_logo.png" />
                        </p>

                        <p style="text-align:center;font-size:1.4em;padding:32px 42px;width:80%;margin:0 auto;margin-top:48px;box-shadow:0 1px 12px 0 rgba(0,0,0,.32);background:white;border-radius:6px;">
                            Oh nein, ein Fehler bei der Erstellung Deiner Rechnung ist aufgetreten!<br>Bitte versuche es erneut oder kontaktiere uns über das <a href="/contact" style="color:orange;text-decoration:none;outline:none;border:0;">Kontaktformular</a> und gib in der Zeile für die Mitteilung Deine Bestellnummer mit an.
                        </p> 
                    </body>

                </html>

            ';
    } else {

        // Output the generated PDF to Browser
        $dompdf->stream($filename, $options);
    }
} else {

    // Output the generated PDF to Browser
    $dompdf->stream($filename, $options);
}

?>