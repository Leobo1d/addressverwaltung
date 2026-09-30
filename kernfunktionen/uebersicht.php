<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adressverwaltung - Übersicht</title>
<link rel="stylesheet" type="text/css" href="..\styles.css" />
</head>

<body>

<table>
    <h2>Alle Einträge</h2>
<tr>
    <th>Vorname</th> 
    <th>Name</th> 
    <th>PLZ</th> 
    <th>Ort</th>
</tr>

<?php

require_once('konfiguration.php');

$query = mysqli_query($mysqli, 

"SELECT personen.personenId, personen.name, personen.vorname, ort.plz, ort.ort
 FROM ort
    LEFT JOIN adresse ON adresse.ortId = ort.ortId
    LEFT JOIN personen ON adresse.personenId = personen.personenId")

or die (mysqli_error($mysqli));

while($row = mysqli_fetch_array($query)) {
    echo
    "<tr>
    <td><a href=\"detail.php?id={$row['personenId']}\">{$row['vorname']}</a></td> 
    <td><a href=\"detail.php?id={$row['personenId']}\">{$row['name']}</a></td> 
    <td><a href=\"detail.php?id={$row['personenId']}\">{$row['plz']}</a></td> 
    <td><a href=\"detail.php?id={$row['personenId']}\">{$row['ort']}</a></td>
    </tr>\n";
}
?>
</table>

    <a href="..\index.php" class="button">Neuer Eintrag</button>

</body>

</html>

<?php 


/*
Gebaute Select Statements

name, vorname, plz und ort:

SELECT personen.name, personen.vorname, adresse.plz, ort.ort
 FROM ort
    LEFT JOIN adresse ON adresse.plz = ort.plz
    LEFT JOIN personen ON adresse.personenId = personen.personenId


*/
?>