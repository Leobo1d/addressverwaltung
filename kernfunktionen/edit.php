<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Adressverwaltung - Bearbeiten</title>
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
//personenId aus vorheriger Datei auslesen
$personenId = $_GET['id'];
$editable = $_GET['edit'];
$adresseId = $_GET['aId'];
$ortId = $_GET['oId']; 

//echo $personenId;
//echo $editable;
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
?>

    </table>
<?php
    //was
    $was = "";
    $auswahl = "";

    switch($editable) {
        case 1:
            $auswahl = "Vorname";
            $was = "vorname";
            break;
        case 2:
            $auswahl = "Name";
            $was = "name";
            break;
        case 3:
            $auswahl = "Adresstyp";
            $was = "adresstyp";
            break;
        case 4:
            $auswahl = "Strasse";
            $was = "strasse";
            break;
        case 5:
            $auswahl = "Hausnummer";
            $was = "hausnummer";
            break;
        case 6:
            $auswahl = "PLZ";
            $was = "plz";
            break;
        case 7:
            $auswahl = "Ort";
            $was = "ort";
            break;
        case 8:
            $auswahl = "Land";
            $was = "land";
            break;
        case 9:
            $auswahl = "Telefon";
            $was = "telefon";
            break;}
    //wodrin
    $wodrin = "";

    if ($auswahl === "Vorname" || $auswahl === "Name" || $auswahl === "Adresstyp" || $auswahl === "Telefon") {
        $wodrin = "personen";
    }
    elseif ($auswahl === "Strasse" || $auswahl === "Hausnummer") {
        $wodrin = "adresse";
    } elseif ($auswahl === "Ort" || $auswahl === "Land" || $auswahl === "PLZ") {
        $wodrin = "ort";
    } else {
        echo "Keine Auswahl getroffen.";
    }

    
    //function zum ändern des Eintrages

    //personen
    function editPersonen(string $was, string $wodrin, string $wozu, string $personenId, mysqli $mysqli) {

        $erlaubteTabellen = ['personen'/*, 'adresse', 'ort'*/];
        $erlaubteSpalten = ['name', 'vorname', 'adresstyp', 'telefon'/*, 'strasse', 'hausnummer', 'ort', 'land'*/];

        if (!in_array($wodrin, $erlaubteTabellen, true) || !in_array($was, $erlaubteSpalten, true)) {
        die('Ungültige Tabelle oder Spalte.');
        }

            $stmt = $mysqli->prepare("UPDATE `$wodrin` SET `$was`=? WHERE personenId=?");
            $stmt->bind_param('ss', $wozu, $personenId);
            $stmt->execute();
        }

        //adresse
        function editAdresse(string $was, string $wodrin, string $wozu, string $adresseId, mysqli $mysqli) {

        $erlaubteTabellen = ['adresse'];
        $erlaubteSpalten = ['strasse', 'hausnummer'];

        if (!in_array($wodrin, $erlaubteTabellen, true) || !in_array($was, $erlaubteSpalten, true)) {
        die('Ungültige Tabelle oder Spalte.');
        }

            $stmt = $mysqli->prepare("UPDATE `$wodrin` SET `$was`=? WHERE adresseId=?");
            $stmt->bind_param('ss', $wozu, $adresseId);
            $stmt->execute();
        }

        //ort
        function editOrt(string $was, string $wodrin, string $wozu, string $ortId, mysqli $mysqli) {

        $erlaubteTabellen = ['ort'];
        $erlaubteSpalten = ['ort', 'land', 'plz'];

        if (!in_array($wodrin, $erlaubteTabellen, true) || !in_array($was, $erlaubteSpalten, true)) {
        die('Ungültige Tabelle oder Spalte.');
        }

            $stmt = $mysqli->prepare("UPDATE `$wodrin` SET `$was`=? WHERE ortId=?");
            $stmt->bind_param('ss', $wozu, $ortId);
            $stmt->execute();
        }


    ?>
    <form action="edit.php?id=<?= $personenId ?>&edit=<?= $editable ?>&aId=<?= $adresseId ?>&oId=<?= $ortId ?>" method="POST">

    <?= $auswahl; ?> bearbeiten <input type="text" name="wozu" value="<?php if(isset($_POST['wozu'])){echo $_POST['wozu']; }?>"><br>

    <button type="submit" name="sichern" class="button">Sichern</button>

    <a href="uebersicht.php" class="button">Zur Übersicht</button>

    </form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sichern'])) {
   /* edit($was, $wodrin, $_POST['wozu'], $personenId, $mysqli)*/
   if ($wodrin === "personen") {
    editPersonen($was, $wodrin, $_POST['wozu'], $personenId, $mysqli);
    }
    elseif ($wodrin === "adresse"){
    editAdresse($was, $wodrin, $_POST['wozu'], $adresseId, $mysqli);

    }
    elseif ($wodrin === "ort"){
    editOrt($was, $wodrin, $_POST['wozu'], $ortId, $mysqli);
    }
    else {echo "Keine Gültige Tabelle.";}
    
    }


/*

&edit=<?= $editable ?>

http://localhost/addressverwaltung/addressverwaltung/kernfunktionen/edit.php

in $_GET:
id = personenId
edit 1-9 =  1 = vorname
            2 = name
            3 = adresstyp
            4 = strasse
            5 = hausnummer
            6 = plz
            7 = ort
            8 = land
            9 = telefon

=== Bearbeiten ===
x -> zelle anwählen
x -> inhalt auslesen (Leo)
x -> position auslesen (vorname)

x -> eingabefeld aufrufen
x -> Gyula schreiben

-> SQL Statement: vorname in Tabelle Personen mit der personenId = $personenId zu Gyula ändern
-> db updaten
-> Seite updaten

*/
?>

    </body>
</html>