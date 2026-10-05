<?php
require_once('konfiguration.php');
include('dbfunction.php');

/*
|--Eingabe neuer adressen
|--Übersicht aller Adressen (vorname, name, plz, ort)
    |--Detailansicht einer adresse
    |--Bearbeiten von adressen
    |--Löschen von adressen
    |--Filter Funktion
    |--Standardmäßige Sortierung (nach Nachname, Vorname, Plz)
*/

//neuer eintrag:

$pruefen = isset($_POST['pruefen']);
$speichern = $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['speichern']);

if ($speichern) {
    datenSpeichern($mysqli, $_POST);
}

//modus wahl -------------------------------------------------------------------------------------------------------

$modus = $_POST['modus'] ?? 'details';

$alleModi = ['details', 'editieren', 'delete'];
if (!in_array($modus, $alleModi, true)) {
    $modus = 'details';
}

$id = isset($_POST['eintrag']) ? (int)$_POST['eintrag'] : null;

$datensatz = null;
if ($id !== null) {
    $datensatz = selectAll($mysqli, $id)->fetch_assoc();
}

//Adress und Orts id definieren

$adresseId = $datensatz['adresseId'] ?? "";
$ortId = $datensatz['ortId'] ?? "";

//wildcard ---------------------------------------------------------------------------------------------------------

$sucher = orderByName($mysqli);

$wildcard = $_POST['wildcard'] ?? "";

if ($wildcard !== "") {
    $sucher = orderByWildcard($mysqli, $wildcard);
} else {
    $sucher = orderByName($mysqli);
}

//edit -------------------------------------------------------------------------------------------------------------

$felder = [
    'name'          => 'personen',
    'vorname'       => 'personen',
    'adresstyp'     => 'personen',
    'telefon'       => 'personen',
    'strasse'       => 'adresse',
    'hausnummer'    => 'adresse',
    'plz'           => 'ort',
    'ort'           => 'ort',
    'land'          => 'ort'
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sichern'], $_POST['edit'])) {
    /* edit($was, $wodrin, $_POST['wozu'], $personenId, $mysqli)*/
    $was = $_POST['edit'];
    if (!isset($felder[$was])) {
        echo 'ungültiges Feld';
    } else {
        $wodrin = $felder[$was];
        $wozu = trim($_POST['wozu'] ?? '');


        switch ($wodrin) {
            case 'personen':
                editPersonen($mysqli, $was, $wodrin, $wozu, $id);
                break;

            case 'adresse':
                editAdresse($mysqli, $was, $wodrin, $wozu, $adresseId);
                break;

            case 'ort':
                editOrt($mysqli, $was, $wodrin, $wozu, $ortId);
                break;
        }
    }
}

// delete -------------------------------------------------------------------------------------------------------------

if (isset($_POST['loeschen'])) {
    adresseLoeschen($mysqli, $id, $ortId);
}

?>

<!-- ----------------------------------------------------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adressverwaltung</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>
    <!-- Startseite = Übersicht mit Button zur Input Seite und Button zur anderen Übersichtsseite -->
    <nav>
        <a href="#" onclick="showPage('datenInput')">Daten hinzufügen</a>
        <a href="#" onclick="showPage('datenÜbersicht')">Übersicht</a>
    </nav>

    <div id="start" class="page active">

    </div>

    <!-- Daten Inputseite -->
    <div id="datenInput" class="page">

        <form action="" method="POST">

            <br><input type="text" name="name" placeholder="* Name/Firmenname:  " required>
            <br><br>
            <input type="radio" id="nachname" name="adresstyp" value="nachname" required>
            <label for="nachname">Nachname *       </label>
            <input type="radio" id="firma" name="adresstyp" value="firma" required>
            <label for="firma">Firma *</label>

            <br>
            <br><input type="text" name="vorname" placeholder="Vorname:">
            <br>
            <br><input type="text" name="strasse" placeholder="* Straße: " required>
            <br>
            <br><input type="text" name="hausnummer" placeholder="* Hausnummer: " required>
            <br>
            <br><input type="text" name="plz" placeholder="* Postleitzahl: " required>
            <br>
            <br><input type="text" name="ort" placeholder="* Ort: " required>
            <br>
            <br><input type="text" name="land" placeholder="* Land: " required>
            <br>
            <br><input type="text" name="telefonnummer" placeholder="Telefonnummer:"><br>

            <br><button class="button" type="submit" name="pruefen">Weiter</button>
        </form>
    </div>
    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Anzeigen der Eingegebenen Daten, dann Speichern -->


    <div id="inputCheck" class="page">
        <form method="POST">

            <table>
                <tr>
                    <th>Vorname</th>
                    <th>Name</th>
                    <th>Adresstyp</th>
                    <th>Straße</th>
                    <th>Hausnummer</th>
                    <th>PLZ</th>
                    <th>Ort</th>
                    <th>Land</th>
                    <th>Telefonnummer</th>
                </tr>

                <?php
                function h(string $k): string
                {
                    return htmlspecialchars($_POST[$k] ?? '');
                }
                ?>
                <tr>
                    <td><?= h('vorname') ?></td>
                    <td><?= h('name') ?></td>
                    <td><?= ucfirst(h('adresstyp')) ?></td>
                    <td><?= h('strasse') ?></td>
                    <td><?= h('hausnummer') ?></td>
                    <td><?= h('plz') ?></td>
                    <td><?= h('ort') ?></td>
                    <td><?= h('land') ?></td>
                    <td><?= h('telefonnummer') ?></td>
                </tr>
            </table>


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
            <button type="submit" name="speichern" class="button">Speichern</button>
        </form>
    </div>
    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- In Übersicht Buttons: Löschen - Editieren - Anzeigen -->

    <div id="datenÜbersicht" class="page">
        <div class="sidenav">
            <form id="auswahl" action="" method="POST"></form>

            <div class="modus">
                <input type="radio" id="m_details" name="modus" value="details" form="auswahl" <?= $modus === 'details' ? 'checked' : '' ?>>
                <label for="m_details">Details</label><br>

                <input type="radio" id="m_edit" name="modus" value="editieren" form="auswahl" <?= $modus === 'editieren' ? 'checked' : '' ?>>
                <label for="m_edit">Editieren</label><br>

                <input type="radio" id="m_delete" name="modus" value="delete" form="auswahl" <?= $modus === 'delete' ? 'checked' : '' ?>>
                <label for="m_delete">Löschen</label><br>

            </div>

            <div class="wildcard">
                <form id="wildc" action="" method="POST">
                    <input type="text" name="wildcard" placeholder="Wildcard" value="<?= htmlspecialchars($wildcard) ?>">
                    <button type="submit">Suchen</button><br>
                </form>
            </div>
        </div>


        <!-- Übersicht über alle vorhandenen Daten, nur Name, Vorname, PLZ und Ort -->

        <table>
            <tr>
                <th>Name</th>
                <th>Vorname</th>
                <th>PLZ</th>
                <th>Ort</th>
            </tr>

            <?php $result = $sucher;
            while ($row = $result->fetch_assoc()): ?>

                <tr>
                    <td><button type="submit" form="auswahl" name="eintrag" value="<?= (int)$row['personenId'] ?>" class="link-button">
                            <?= htmlspecialchars($row['name']) ?>
                        </button>
                    </td>
                    <td><?= htmlspecialchars($row['vorname']) ?></td>
                    <td><?= htmlspecialchars($row['plz']) ?></td>
                    <td><?= htmlspecialchars($row['ort']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

    <!-- Anzeigen der Informationene einer Adresse (Schreibgeschützt) -->
    <div id="details" class="page">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Vorname</th>
                <th>Adresstyp</th>
                <th>Straße</th>
                <th>Hausnummer</th>
                <th>PLZ</th>
                <th>Ort</th>
                <th>Land</th>
                <th>Telefonnummer</th>
            </tr>

            <?php if ($id !== null):
                $result = selectAll($mysqli, $id);
                while ($row = $result->fetch_assoc()): ?>

                    <tr>
                        <td><?= htmlspecialchars($row['personenId']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['vorname']) ?></td>
                        <td><?= htmlspecialchars($row['adresstyp']) ?></td>
                        <td><?= htmlspecialchars($row['strasse']) ?></td>
                        <td><?= htmlspecialchars($row['hausnummer']) ?></td>
                        <td><?= htmlspecialchars($row['plz']) ?></td>
                        <td><?= htmlspecialchars($row['ort']) ?></td>
                        <td><?= htmlspecialchars($row['land']) ?></td>
                        <td><?= htmlspecialchars($row['telefon']) ?></td>
                    </tr>
            <?php endwhile;
            endif; ?>
        </table>
        <a href="#" onclick="showPage('datenÜbersicht')" class="button">Zurück zur Übersicht</a>
    </div>
    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Löschen einer Gesamten Adresse -->
    <div id="editieren" class="page">
        <form id="edit" action="" method="POST"></form>

        <table>
            <tr>
                <th>Name</th>
                <th>Vorname</th>
                <th>Adresstyp</th>
                <th>Straße</th>
                <th>Hausnummer</th>
                <th>PLZ</th>
                <th>Ort</th>
                <th>Land</th>
                <th>Telefonnummer</th>
            </tr>

            <?php if ($id !== null): $result = selectAll($mysqli, $id);
                while ($row = $result->fetch_assoc()): ?>

                    <tr>
                        <td>
                            <input type="radio" id="e_name" name="edit" value="name" form="edit">
                            <label for="e_name"><?= htmlspecialchars($row['name']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_vorname" name="edit" value="vorname" form="edit">
                            <label for="e_vorname"><?= htmlspecialchars($row['vorname']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_adresstyp" name="edit" value="adresstyp" form="edit">
                            <label for="e_adresstyp"><?= htmlspecialchars($row['adresstyp']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_strasse" name="edit" value="strasse" form="edit">
                            <label for="e_strasse"><?= htmlspecialchars($row['strasse']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_hausnummer" name="edit" value="hausnummer" form="edit">
                            <label for="e_hausnummer"><?= htmlspecialchars($row['hausnummer']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_plz" name="edit" value="plz" form="edit">
                            <label for="e_plz"><?= htmlspecialchars($row['plz']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_ort" name="edit" value="ort" form="edit">
                            <label for="e_ort"><?= htmlspecialchars($row['ort']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_land" name="edit" value="land" form="edit">
                            <label for="e_land"><?= htmlspecialchars($row['land']) ?></label>
                        </td>
                        <td>
                            <input type="radio" id="e_tel" name="edit" value="telefon" form="edit">
                            <label for="e_tel"><?= htmlspecialchars($row['telefon']) ?></label>
                        </td>
                    </tr>
            <?php endwhile;
            endif; ?>
        </table>

        <input form="edit" type="hidden" name="eintrag" value="<?= (int)$id ?>">
        <input form="edit" type="hidden" name="modus" value="editieren">

        <input form="edit" type="text" name="wozu" placeholder="Eintrag bearbeiten" value="<?php if (isset($_POST['wozu'])) {
                                                                                                echo $_POST['wozu'];
                                                                                            } ?>"><br>

        <button form="edit" type="submit" name="sichern" class="button">Sichern</button>
        <a href="#" onclick="showPage('datenÜbersicht')" class="button">Zurück zur Übersicht</a>
    </div>

    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Editieren eines Eintrags in einer Adresse -->
    <div id="delete" class="page">
        <form id="del" method="POST" action=""></form>
        <table>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Name</th>
                <th>Adresstyp</th>
                <th>Straße</th>
                <th>Hausnummer</th>
                <th>PLZ</th>
                <th>Ort</th>
                <th>Land</th>
                <th>Telefonnummer</th>
            </tr>

            <?php if ($id !== null):
                $result = selectAll($mysqli, $id);
                while ($row = $result->fetch_assoc()): ?>

                    <tr>
                        <td><?= htmlspecialchars($row['personenId']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['vorname']) ?></td>
                        <td><?= htmlspecialchars($row['adresstyp']) ?></td>
                        <td><?= htmlspecialchars($row['strasse']) ?></td>
                        <td><?= htmlspecialchars($row['hausnummer']) ?></td>
                        <td><?= htmlspecialchars($row['plz']) ?></td>
                        <td><?= htmlspecialchars($row['ort']) ?></td>
                        <td><?= htmlspecialchars($row['land']) ?></td>
                        <td><?= htmlspecialchars($row['telefon']) ?></td>
                    </tr>
            <?php endwhile;
            endif; ?>

        </table>


        <input form="del" type="hidden" name="eintrag" value="<?= (int)$id ?>">
        <input form="del" type="hidden" name="modus" value="delete">

        <button form="del" class="button" type="submit" name="loeschen">Löschen</button>
        <a href="#" onclick="showPage('datenÜbersicht')" class="button">Abbrechen - zur Übersicht</a>

    </div>

    <script>
        function showPage(pageId) {
            const pages = document.querySelectorAll('.page');
            pages.forEach(page => page.classList.remove('active'));

            document.getElementById(pageId).classList.add('active');
        }

        <?php if ($pruefen): ?>
            showPage('inputCheck');
        <?php elseif ($speichern): ?>
            showPage('datenÜbersicht');
        <?php elseif ($datensatz !== null): ?>
            showPage(<?= json_encode($modus) ?>);
        <?php elseif (isset($_POST['wildcard'])): ?>
            showPage('datenÜbersicht');
        <?php endif; ?>
    </script>

</body>

</html>