<?php

declare(strict_types=1);

use Spatie\Browsershot\Browsershot;

require __DIR__ . '/vendor/autoload.php';

// Rechnungsinformationen
$rechnungsnummer = '123';
$erstellungsdatum = '01.01.2024';
$faelligkeitsdatum = '15.01.2024';

// Firmeninformationen
$firmenname = 'Firmenname';
$firmenadresse = 'Straße und Hausnummer';
$firma_plz_stadt = 'PLZ und Stadt';

// Kundeninformationen
$kundennummer = '456';
$kundenname = 'Kundenname';
$kundenadresse = 'Straße und Hausnummer';
$kunde_plz_stadt = 'PLZ und Stadt';

// Produkte und Beträge
$produkte = [
    ['beschreibung' => 'Produkt 1', 'betrag' => 200.00],
    ['beschreibung' => 'Produkt 2', 'betrag' => 200.00],
    ['beschreibung' => 'Produkt 3', 'betrag' => 150.00],
];

// Gesamtbetrag berechnen
$gesamtbetrag = 0;
foreach ($produkte as $produkt) {
    $gesamtbetrag += $produkt['betrag'];
}

$html = "
<!DOCTYPE html>
<html lang=\"de\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Rechnung</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #fff;
        }
        .invoice-box {
            width: 210mm;
            min-height: 297mm;
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }
        .invoice-box table {
            width: 100%;
            line-height: 24px;
            text-align: left;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class=\"invoice-box\">
        <table>
            <tr class=\"top\">
                <td colspan=\"2\">
                    <table>
                        <tr>
                            <td class=\"title\">
                                <h2>Rechnung</h2>
                            </td>
                            <td>
                                Rechnungsnummer: " . $rechnungsnummer . "<br>
                                Erstellungsdatum: " . $erstellungsdatum . "<br>
                                Fälligkeitsdatum: " . $faelligkeitsdatum . "
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class=\"information\">
                <td colspan=\"2\">
                    <table>
                        <tr>
                            <td>
                                " . $firmenname . "<br>
                                " . $firmenadresse . "<br>
                                " . $firma_plz_stadt . "
                            </td>
                            <td>
                                Kundennummer: " . $kundennummer . "<br>
                                " . $kundenname . "<br>
                                " . $kundenadresse . "<br>
                                " . $kunde_plz_stadt . "
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class=\"heading\">
                <td>Beschreibung</td>
                <td>Betrag</td>
            </tr>
            ";
            foreach ($produkte as $produkt) {
                $html .= "
                <tr class=\"item\">
                    <td>" . $produkt['beschreibung'] . "</td>
                    <td>" . number_format($produkt['betrag'], 2, ',', '.') . " €</td>
                </tr>";
            }
            $html .= "
            <tr class=\"total\">
                <td></td>
                <td>Gesamt: " . number_format($gesamtbetrag, 2, ',', '.') . " €</td>
            </tr>
        </table>
    </div>
</body>
</html>
";

Browsershot::html($html)->showBackground()->format('A4')->save("linuxmint.pdf");
