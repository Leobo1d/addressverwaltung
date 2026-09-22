<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adressverwaltung</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>

<div class="overview">

    <h4>Daten</h4>
    <p>
    Name: <?= $_POST["name"]; ?> <?php 
    if($_POST["adresstyp"] === "firma") {echo "(" . ucfirst($_POST["adresstyp"]) . ")";
    } 
    else {
        echo "";
    } ?><br>    
    Vorname: <?= $_POST["vorname"] ?><br><br>
    </p>
    <p>
    Strasse: <?= $_POST["strasse"] ?><br>
    Hausnummer: <?= $_POST["hausnummer"] ?><br>
    PLZ: <?= $_POST["plz"] ?><br>
    Ort: <?= $_POST["ort"] ?><br>
    Land: <?= $_POST["land"] ?><br><br>
    </p>
    <p>
    Telefonnummer: <?= $_POST["telefonnummer"] ?><br><br>
    </p>

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


    Diese Daten speichern? 
    <br>
    <button type="submit" name="speichern" class="button">Speichern</button>
    <br>

    </form>
</div>

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
$name = strtolower($name);
$vorname = strtolower($vorname);
$strasse = strtolower($strasse);
$ort = strtolower($ort);
$land = strtolower($land);

$tel = preg_replace("/[^a-zA-Z0-9_äöüÄÖÜ? ]/u", "", $tel);


//echo $name . $vorname . $strasse . $hausnummer . $plz . $ort . $land . $tel . $adresstyp;

//Insert in die Datenbank
    // ort
    $stmt = $mysqli->prepare("INSERT INTO ort(plz, ort, land) VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE ort = VALUES(ort), land = VALUES(land)");
    $stmt->bind_param("sss", $plz, $ort, $land);
    $stmt->execute();

    // personen
    $stmt = $mysqli->prepare("INSERT INTO personen (name, vorname, adresstyp, telefon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $vorname, $adresstyp, $tel);
    $stmt->execute();
    $personenId = $mysqli->insert_id;

    // adresse
    $stmt = $mysqli->prepare("INSERT INTO adresse(strasse, hausnummer, plz, personenId) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $strasse, $hausnummer, $plz, $personenId);
    $stmt->execute();
    $adresseId = $mysqli->insert_id;

}

//Abfrage nach dem drücken des Speichern buttons, dann ausführen der speichern Funktion ^
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['speichern'])) {
    speichern($_POST, $mysqli);
}

?>