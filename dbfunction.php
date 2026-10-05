<?php
require_once('konfiguration.php');
/*
datenSpeichern($mysqli, array $userInput)                   --> Speichert Eingegebene Daten in die Tabellen
selectAll($mysqli, string $pId)                             --> Zeigt alle vorhandenen Einträge aus allen Tabellen
selectUbersicht($mysqli)                                    --> Zeigt Name, Vorname, PLZ, Ort
orderByName($mysqli)                                        --> Sortiert die Ausgabe nach Name, Vorname, PLZ
orderByWildcard($mysqli, $wildcard)                         --> Sortiert/Zeigt an anhand einer mitgegebenen Wildcard
editPersonen($mysqli, $was, $wodrin, $wozu, $personenId)    --> Editiert einen Wert in der Personen Tabelle
editAdresse($mysqli, $was, $wodrin, $wozu, $adresseId)      --> Editiert eine Wert in der Adresse Tabelle
editOrt($mysqli, $was, $wodrin, $wozu, $ortId)              --> Editiert einen Wert in der Ort Tabelle
adresseLoeschen($mysqli, $personenId, $ortId)               --> Löscht alle Informationen einer Adresse aus allen Tabellen
*/

//Speichern

function getId($mysqli, array $array)
{
    $id = $array['personenId'];
    return $id;
}
function datenSpeichern(mysqli $mysqli, array $userInput)
{
    $name = $userInput["name"];
    $vorname = $userInput["vorname"];
    $strasse = $userInput["strasse"];
    $hausnummer = $userInput["hausnummer"];
    $plz = $userInput["plz"];
    $ort = $userInput["ort"];
    $land = $userInput["land"];
    $tel = $userInput["telefonnummer"];
    $adresstyp = $userInput["adresstyp"];

    $tel = preg_replace("/[^-zA-Z0-9_äöüÄÖÜ? ]/u", "", $tel);

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

    return "Daten in der Datenbank gespeichert";
}

//SELECT
function selectAll(mysqli $mysqli, string $pId)
{
    $stmt = $mysqli->prepare("  SELECT personen.*, ort.*, adresse.* 
                                    FROM ort  
                                LEFT JOIN adresse  ON adresse.ortId = ort.ortId 
                                LEFT JOIN personen  ON adresse.personenId = personen.personenId 
                                    WHERE personen.personenId = ?");
    $stmt->bind_param("s", $pId);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result;
}

function selectUbersicht(mysqli $mysqli)
{
    $stmt = $mysqli->prepare("  SELECT personen.name, personen.vorname, ort.plz, ort.ort, personen.personenId 
                                    FROM personen 
                                LEFT JOIN adresse  ON adresse.personenId = personen.personenId 
                                LEFT JOIN ort  ON ort.ortId = adresse.ortId");
    $stmt->execute();

    $result = $stmt->get_result();
    return $result;
}

//Sortieren
//Name, Vorname, PLZ
function orderByName(mysqli $mysqli)
{
    $stmt = $mysqli->prepare("  SELECT personen.name, personen.vorname, ort.plz, ort.ort, personen.personenId
                                    FROM personen 
                                LEFT JOIN adresse  ON adresse.personenId = personen.personenId 
                                LEFT JOIN ort  ON ort.ortId = adresse.ortId
                                    ORDER BY personen.name, personen.vorname, ort.plz");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

//Wildcard
function orderByWildcard(mysqli $mysqli, string $wildcard)
{

    $sql = "SELECT personen.personenId, personen.name, personen.vorname, ort.plz, ort.ort
        FROM personen
        LEFT JOIN adresse ON adresse.personenId = personen.personenId
        LEFT JOIN ort ON ort.ortId = adresse.ortId";

    $params = [];

    if ($wildcard !== '') {
        $sql .= " WHERE personen.name LIKE ? OR personen.vorname LIKE ?
              OR ort.plz LIKE ? OR ort.ort LIKE ?";

        $like = '%' . $wildcard . '%';
        $params = [$like, $like, $like, $like];
    }

    $stmt = $mysqli->prepare($sql);
    if ($params) {
        $stmt->bind_param('ssss', ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

//Bearbeiten
//personen
function editPersonen(mysqli $mysqli, string $was, string $wodrin, string $wozu, string $personenId)
{
    $erlaubteTabellen = ['personen'];
    $erlaubteSpalten = ['name', 'vorname', 'adresstyp', 'telefon'];

    if (!in_array($wodrin, $erlaubteTabellen, true) || !in_array($was, $erlaubteSpalten, true)) {
        die('Ungültige Tabelle oder Spalte.');
    }

    $stmt = $mysqli->prepare("UPDATE `$wodrin` SET `$was`=? WHERE personenId=?");
    $stmt->bind_param('ss', $wozu, $personenId);
    $stmt->execute();

    return "Person wurde bearbeitet";
}
//adresse
function editAdresse(mysqli $mysqli, string $was, string $wodrin, string $wozu, string $adresseId)
{
    $erlaubteTabellen = ['adresse'];
    $erlaubteSpalten = ['strasse', 'hausnummer'];

    if (!in_array($wodrin, $erlaubteTabellen, true) || !in_array($was, $erlaubteSpalten, true)) {
        die('Ungültige Tabelle oder Spalte.');
    }

    $stmt = $mysqli->prepare("UPDATE `$wodrin` SET `$was`=? WHERE adresseId=?");
    $stmt->bind_param('ss', $wozu, $adresseId);
    $stmt->execute();

    return "Adresse wurde bearbeitet";
}
//ort
function editOrt(mysqli $mysqli, string $was, string $wodrin, string $wozu, string $ortId)
{
    $erlaubteTabellen = ['ort'];
    $erlaubteSpalten = ['ort', 'land', 'plz'];

    if (!in_array($wodrin, $erlaubteTabellen, true) || !in_array($was, $erlaubteSpalten, true)) {
        die('Ungültige Tabelle oder Spalte.');
    }

    $stmt = $mysqli->prepare("UPDATE `$wodrin` SET `$was`=? WHERE ortId=?");
    $stmt->bind_param('ss', $wozu, $ortId);
    $stmt->execute();

    return "Ort wurde bearbeitet";
}

//Löschen
function adresseLoeschen(mysqli $mysqli, string $personenId, string $ortId)
{
    $stmt = $mysqli->prepare("DELETE FROM adresse WHERE personenId = ?");
    $stmt->bind_param("s", $personenId);
    $stmt->execute();

    $stmt = $mysqli->prepare("DELETE FROM personen WHERE personenId = ?");
    $stmt->bind_param("s", $personenId);
    $stmt->execute();

    $stmt = $mysqli->prepare("DELETE FROM ort WHERE ortId = ?");
    $stmt->bind_param("s", $ortId);
    $stmt->execute();

    return "Löschen erfolgreich";
}
