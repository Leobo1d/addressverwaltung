CREATE TABLE ort (
ortId int PRIMARY KEY AUTO_INCREMENT,
plz varchar(10),
ort varchar(150),
land varchar(150)
);

CREATE TABLE personen (
personenId int PRIMARY KEY AUTO_INCREMENT,
name varchar(150) NOT NULL,
vorname varchar(150),
adresstyp varchar(8),
telefon varchar(15)
);

CREATE TABLE adresse (
adresseId int PRIMARY KEY AUTO_INCREMENT,
strasse varchar(150),
hausnummer varchar(150),
personenId int,
ortId int,
CONSTRAINT fk_ort
FOREIGN KEY (ortId)
REFERENCES ort(ortId),
CONSTRAINT fk_personen
FOREIGN KEY (personenId)
REFERENCES personen(personenId)
);