<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adressverwaltung - Details</title>
<link rel="stylesheet" type="text/css" href="..\styles.css" />
</head>

<body>

<table>
    <h2>Adressdetails</h2>

    <tr>
        <th>ID</th>
        <th>Vorname</th>
        <th>Name</th>
        <th>Adresstyp</th>
        <th>Strasse</th>
        <th>Hausnummer</th>
        <th>PLZ</th>
        <th>Ort</th>
        <th>Land</th>
        <th>Telefonnummer</th>
    </tr>
<?php
require_once('konfiguration.php');
//personenId aus vorheriger Datei auslesen
$personenId = $_GET['id'];
//echo $personenId;

$query = mysqli_query($mysqli,

"SELECT personen.*, ort.*, adresse.*
FROM ort
    LEFT JOIN adresse ON adresse.ortId = ort.ortId
    LEFT JOIN personen ON adresse.personenId = personen.personenId
WHERE personen.personenId = '$personenId'");

while($row = mysqli_fetch_array($query)) {

    $url = 'edit.php';

    $parameters = ['id' => $row['personenId'], 'aId' => $row['adresseId'], 'oId' => $row['ortId']];

    $url .= '?'.http_build_query($parameters);



    $adresstyp = ucfirst($row['adresstyp']);

    echo
    "<tr>
    <td>{$row['personenId']}</td>
    <td><a href=\"$url&edit=1\">{$row['vorname']}</a></td>
    <td><a href=\"$url&edit=2\">{$row['name']}</a></td>
    <td><a href=\"$url&edit=3\">{$adresstyp}</a></td>
    <td><a href=\"$url&edit=4\">{$row['strasse']}</a></td>
    <td><a href=\"$url&edit=5\">{$row['hausnummer']}</a></td>
    <td><a href=\"$url&edit=6\">{$row['plz']}</a></td>
    <td><a href=\"$url&edit=7\">{$row['ort']}</a></td>
    <td><a href=\"$url&edit=8\">{$row['land']}</a></td>
    <td><a href=\"$url&edit=9\">{$row['telefon']}</a></td>
    </tr>\n";
}
?>

</table>

<a href="uebersicht.php" class="button">Zur Übersicht</button>

<a href="loeschen.php?id=<?= $personenId;?>" class="button">Eintrag löschen</button>
</body>
</html>