# addressverwaltung

Adressverwaltung (Datenbank)

Es soll eine Webanwendung erstellt werden, mit der Adressen von Personen und Firmen 
gespeichert werden können. 

Die Daten sollen in einem RDBMS (Relationalen Datenbank Management System, z.B. MySQL) 
gespeichert werden. Von dort sollen die Daten in der Webanwendung auch wieder abrufbar und 
durchsuchbar sein. Das Datenmodell soll erweiterbar sein. 

Folgende Informationen sollen in einzelnen Feldern gespeichert werden können: 
    
    - Name bzw. Firmenname 
    - Vorname 
    - Strasse 
    - Hausnummer 
    - PLZ 
    - Ort 
    - Land 
    - Telefonnummer 
    - Adresstyp (Firma / Person) 

1. ~~Erstelle ein passendes Datenmodell.~~ 

2. ~~Erstelle eine Eingabeseite für die Daten in PHP. Übergebe die Daten an eine weitere Seite,~~ 
~~    die zunächst diese Daten in der Datenbank speichert und danach zur Kontrolle erneut ~~
~~    geordnet anzeigt. Dabei soll jeweils ein Label Auskunft darüber geben, welche Information~~ 
~~    in welches Eingabefeld zu schreiben ist bzw. welche Information wo angezeigt wird. ~~
    
~~Es soll eine Funktion speichern() geben, die das Speichern in der Datenbank übernimmt.~~ 

3. Übersicht und Kernfunktionen 
    3.1 Erstelle eine Übersichtsseite aller in der Datenbank gespeicherten Adressen. Es sollen 
        nur Vorname, Name, PLZ und Ort (in dieser Reihenfolge) tabellarisch ausgegeben 
        werden. 
    
    3.2 Erweitere die Übersichtsseite um folgende Funktionen, die jeweils eine eigene Seite 
        (und auch .php-Datei) aufrufen in der alle vorliegenden Informationen einer Adresse 
        anzeigt / bearbeitet werden. 
    
        3.2.1 Anzeige in einer Adressdetail-Seite 
        3.2.2 Bearbeiten der Adresse in einer Bearbeitungsseite 
        3.2.3 Löschen einer Adresse nach Ausgabe auf einer Bestätigungsseite. 
    
    3.3 In der Übersichtsseite sollen die Adressen nach allen Feldern gefiltert werden können. 
        Es soll die Möglichkeit bestehen, dabei auch Wildcards ( % (beliebig viele Zeichen), _ 
        (genau ein Zeichen) ) verwenden zu können. 
    
    3.4 Stelle eine Sortierung der ausgegebenen Daten in der Übersichtsseite sicher. Die 
        Sortierung soll wie folgt sein: Nachname, Vorname, PLZ. 
    
    3.5 Ziehe die 4 php-Dateien aus 2. und 3.2.1 bis 3.2.3 zu einer Datei zusammen. 

4. Erweitere den Funktionsumfang der Übersichtsseite um folgende Punkte: 
    4.1 Hinzufügen von Adressen 
    4.2 Kopieren einer Adresse 
    4.3 Export einer Adresse als VCF 
    4.4 Import einer Adresse aus einem VCF-File 

5. Erweitere den Informationsumfang um folgende Felder: 
    5.1 Anrede (Herr / Frau / Firma) 
    5.2 Mehrere Telefonnummern sollen möglich und qualifizierbar (Mobil / Festnetz / Arbeit / 
        Privat) sein. 
    5.3 URL einer Webseite 
    5.4 Zugehörigkeit von Adressen zu anderen Adressen (Person zu Firma) 

6. Aufbauend auf Aufgabe 5 ändere die Ausgabe der Übersichtsseite wie folgt 
    6.1 im ungefilterten Zustand werden die zu einer Firma gehörende Personen eingerückt 
        unter der Firma der sie zugeordnet sind, dargestellt 
    6.2 im gefilterten Zustand werden die auf den Filter passenden Adressen sortiert ohne 
        Einrückung dargestellt 

7. Alle erzeugten Webseiten sollen in einem einheitlichen Look & Feel angeboten werden. 
    Dafür soll eine zentrale .css-Datei verwendet werden, die für die verwendeten Elemente 
    sinnvolle Vorgaben macht. 

8. Gebe das in 4.3 erstellte VCF als QR Code dar. Verwende dafür die Library phpqrcode. 

Grundsätzlich soll nach jedem Aufgabenhauptpunkt (1.,2., etc.) das bisherige Arbeitsergebnis incl. 
eines SQL-Dumps gezippt an den Tutoren per mail geschickt werden.