CREATE TABLE personen (
    personenId int PRIMARY KEY,
    name varchar(150) NOT NULL,
    vorname varchar(150),
    adresstyp varchar(5),
    telefon varchar(15)
);

CREATE TABLE adresse (
    adresseId int PRIMARY KEY,
    strasse varchar(150),
    hausnummer varchar(150),
    plz varchar,
    personenId int,
    CONSTRAINT fk_ort
    FOREIGN KEY (plz)
    REFERENCES ort(plz)
    CONSTRAINT fk_personen
    FOREIGN KEY (personenId)
    REFERENCES personen(personenId)
);

CREATE TABLE ort (
    plz varchar PRIMARY KEY,
    ort varchar(150),
    land varchar(150)
);