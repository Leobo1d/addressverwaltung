<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Adressverwaltung - Löschen</title>
        <link rel="stylesheet" type="text/css" href="..\styles.css" />
    </head>

    <body>

    <table>
    <h2>Details bearbeiten</h2>

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
    $personenId = $_GET['id'];

    $query = mysqli_query($mysqli,

"SELECT personen.*, ort.*, adresse.*
FROM ort
    LEFT JOIN adresse ON adresse.ortId = ort.ortId
    LEFT JOIN personen ON adresse.personenId = personen.personenId
WHERE personen.personenId = '$personenId'");


while($row = mysqli_fetch_array($query)) {

    $adresstyp = ucfirst($row['adresstyp']);

    echo
    "<tr>
    <td>{$row['personenId']}</td>
    <td>{$row['vorname']}</td>
    <td>{$row['name']}</td>
    <td>{$adresstyp}</td>
    <td>{$row['strasse']}</td>
    <td>{$row['hausnummer']}</td>
    <td>{$row['plz']}</td>
    <td>{$row['ort']}</td>
    <td>{$row['land']}</td>
    <td>{$row['telefon']}</td>
    </tr>\n";
}

    function adresseLoeschen(mysqli $mysqli, string $personenId, string $ortId) {
        
        $stmt = $mysqli->prepare("DELETE FROM adresse WHERE personenId = ?");
        $stmt->bind_param("s", $personenId);
        $stmt->execute();
    
        $stmt = $mysqli->prepare("DELETE FROM personen WHERE personenId = ?");
        $stmt->bind_param("s", $personenId);
        $stmt->execute();

        $stmt = $mysqli->prepare("DELETE FROM ort WHERE ortId = ?");
        $stmt->bind_param("s", $ortId);
        $stmt->execute();


    }
?>
    </table>
    
<?php

 $uebersicht = "Abbrechen";

     $ortId = $personenId;
        if (isset($_POST['loeschen'])) {
            adresseLoeschen($mysqli, $personenId, $ortId);
            $uebersicht = "Löschen erfolgreich - zurük zur Übersicht";
        }
    
?>

<h3>Diese Adresse löschen?</h3>
    <form method="post" action="">
        <button class="button" type="submit" name="loeschen">Löschen</button>
        <a href="uebersicht.php" class="button"><?= $uebersicht; ?></button>
    </form>
    </body>
    </html>