<?php

error_reporting(E_ALL);

//Anmeldedaten von MySQL definieren
define('MYSQL_HOST', 'localhost');
define('MYSQL_BENUTZER', "root");
define('MYSQL_KENNWORT', "dbpw1");
define('MYSQL_DATENBANK', 'adressverwaltung');

//Mit MySQL verbinden
$mysqli = mysqli_connect(
    MYSQL_HOST,
    MYSQL_BENUTZER,
    MYSQL_KENNWORT,
    MYSQL_DATENBANK
);

mysqli_set_charset($mysqli, 'utf8');

if($mysqli) {
    echo "Verbindung erfolgreich";
}