<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adressverwaltung - Eintragen</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>

<form action="dbinput.php" method="POST">
    <br><input type="text" name="name" placeholder="Name/Firmenname: " required> *
    <br><br>
        <input type="radio" id="nachname" name="adresstyp" value="nachname" required>
        <label for="nachname">Nachname</label>
        <input type="radio" id="firma" name="adresstyp" value="firma" required>
        <label for="firma">Firma</label> *
        
    <br>
    <br><input type="text" name="vorname" placeholder="Vorname">
    <br>
    <br><input type="text" name="strasse" placeholder="Straße: " required> *
    <br>
    <br><input type="text" name="hausnummer" placeholder="Hausnummer: " required> *
    <br>
    <br><input type="text" name="plz" placeholder="Postleitzahl: " required> *
    <br>
    <br><input type="text" name="ort" placeholder="Ort: " required> *
    <br>
    <br><input type="text" name="land" placeholder="Land: " required> *
    <br>
    <br><input type="text" name="telefonnummer" placeholder="Telefonnummer:"><br>


    <br><button class="button" type="submit">Weiter</button> <a href="kernfunktionen\uebersicht.php" class="button">Zur Übersicht</button>
</form>
</body>
</html>