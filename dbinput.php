<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adressverwaltung - Einfügen</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>

    <table>
    <h2>Daten</h2>

    <tr>
        <th>Vorname</th>
        <th>Name</th>
        <th>Adresstyp</th>
    </tr>
    <tr>
        <td><?= $_POST['vorname']; ?></td>
        <td><?= $_POST['name']; ?></td>
        <td><?= ucfirst($_POST['adresstyp']); ?></td>
    </tr>
    <tr>
        <th>Strasse</th>
        <th>Hausnummer</th>
    </tr>
    <tr>
        <td><?= $_POST['strasse']; ?></td>
        <td><?= $_POST['hausnummer']; ?></td>
    </tr>
    <tr>
        <th>PLZ</th>
        <th>Ort</th>
        <th>Land</th>
        <th>Telefonnummer</th>
    </tr>
        <tr>    
        <td><?= $_POST['plz']; ?></td>
        <td><?= $_POST['ort']; ?></td>
        <td><?= $_POST['land']; ?></td>
        <td><?= $_POST['telefonnummer']; ?></td>
    </tr>
    </table>

    <form method="POST" action="dbinput.php">

        <input type="hidden" name="name" value="<?= htmlspecialchars($_POST['name']) ?>">
        <input type="hidden" name="vorname" value="<?= htmlspecialchars($_POST['vorname']) ?>">
        <input type="hidden" name="strasse" value="<?= htmlspecialchars($_POST['strasse']) ?>">
        <input type="hidden" name="hausnummer" value="<?= htmlspecialchars($_POST['hausnummer']) ?>">
        <input type="hidden" name="plz" value="<?= htmlspecialchars($_POST['plz']) ?>">
        <input type="hidden" name="ort" value="<?= htmlspecialchars($_POST['ort']) ?>">
        <input type="hidden" name="land" value="<?= htmlspecialchars($_POST['land']) ?>">
        <input type="hidden" name="telefonnummer" value="<?= htmlspecialchars($_POST['telefonnummer']) ?>">
        <input type="hidden" name="adresstyp" value="<?= htmlspecialchars($_POST['adresstyp']) ?>">


    <br>
    <?php $speichern = "Speichern"; ?>
    <button type="submit" name="speichern" class="button"><?= $speichern; ?></button>
    <a href="index.php" class="button">Zurück</button>
    <a href="kernfunktionen\uebersicht.php" class="button">Zur Übersicht</button>

    </form>

</body>

</html>

<?php 

require_once('konfiguration.php');
function speichern(array $userinput, mysqli $mysqli) {


//$_POST Array Inhalt in Variablen für einfache Verwendung in der function sichern
//kunden
$name = $userinput["name"];
$vorname = $userinput["vorname"];
$strasse = $userinput["strasse"];
$hausnummer = $userinput["hausnummer"];
$plz = $userinput["plz"];
$ort = $userinput["ort"];
$land = $userinput["land"];
$tel = $userinput["telefonnummer"];
$adresstyp = $userinput["adresstyp"];

//alles ready machen für die datenbank (Alle Strings in kleinbuchstaben, sonderzeichen aus der Telefonnummer entfernen, falls vorhanden)

$tel = preg_replace("/[^a-zA-Z0-9_äöüÄÖÜ? ]/u", "", $tel);


//echo $name . $vorname . $strasse . $hausnummer . $plz . $ort . $land . $tel . $adresstyp;

//Insert in die Datenbank
    // ort
    $stmt = $mysqli->prepare("INSERT INTO ort(plz, ort, land) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $plz, $ort, $land);
    $stmt->execute();
    $ortId = $mysqli->insert_id;

    // personen
    $stmt = $mysqli->prepare("INSERT INTO personen (name, vorname, adresstyp, telefon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $vorname, $adresstyp, $tel);
    $stmt->execute();
    $personenId = $mysqli->insert_id;

    // adresse
    $stmt = $mysqli->prepare("INSERT INTO adresse(strasse, hausnummer, personenId, ortId) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $strasse, $hausnummer, $personenId, $ortId);
    $stmt->execute();
    $adresseId = $mysqli->insert_id;

}

//Abfrage nach dem drücken des Speichern buttons, dann ausführen der speichern Funktion ^
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['speichern'])) {
    speichern($_POST, $mysqli);
    $speichern = "Speichern erfolgreich";
}


?>