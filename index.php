<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adressverwaltung</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>

<form action="dbinput.php" method="POST">
    Name/Firmenname: <br><input type="text" name="name" required> *<br>
    <br>
        <input type="radio" id="nachname" name="adresstyp" value="nachname" required>
        <label for="nachname">Nachname</label>
        <input type="radio" id="firma" name="adresstyp" value="firma" required>
        <label for="nachname">Firma</label> *<br>
        
    <br>
    Vorname: <br><input type="text" name="vorname"><br>
    <br>
    Strasse: <br><input type="text" name="strasse" required> *<br>
    <br>
    Hausnummer: <br><input type="text" name="hausnummer" required> *<br>
    <br>
    PLZ: <br><input type="text" name="plz" required> *<br>
    <br>
    Ort: <br><input type="text" name="ort" required> *<br>
    <br>
    Land: <br><input type="text" name="land" required> *<br>
    <br>
    Telefonnummer: <br><input type="text" name="telefonnummer"><br>


    <br><input class="button" type="submit">
</form>

</body>
</html>