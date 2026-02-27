-- Création de la base de donnee
CREATE DATABASE IF NOT EXISTS ibant_pay;
USE ibant_pay;

-- Table Classe
CREATE TABLE classe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeClasse VARCHAR(50) NOT NULL UNIQUE,
    libelleClasse VARCHAR(100) NOT NULL
);

-- Table Etudiant
CREATE TABLE etudiant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matriEt VARCHAR(50) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    mail VARCHAR(100),
    photo VARCHAR(255) DEFAULT NULL,
    idClasse INT,
    FOREIGN KEY (idClasse) REFERENCES classe(id) ON DELETE CASCADE
);

-- Table Paiement
CREATE TABLE paiement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    numPaie VARCHAR(50) NOT NULL UNIQUE,
    datePaie DATE NOT NULL,
    montant DOUBLE NOT NULL,
    anneeAc VARCHAR(20) NOT NULL,
    FOREIGN KEY (etudiant_id) REFERENCES etudiant(id) ON DELETE CASCADE
);
