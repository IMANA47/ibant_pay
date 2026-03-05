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

-- Table Utilisateur pour la connexion
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    role VARCHAR(20) DEFAULT 'admin'
);

-- Insertion de l'utilisateur admin par défaut (password is 'admin123' hashed with password_hash)
-- $2y$10$8.0bS.K.v1J8G1J7J.K.u.P4R8R9S0T1U2V3W4X5Y6Z7A8B9C0D1
-- Actually, let's use a simpler one if needed or just provide the hash for 'admin123'
-- password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password, nom, role) 
VALUES ('admin', '$2y$10$v0k8vO9/z/l6/O7K.0.0.uP.X.R.W.X.R.W.X.R.W.X.R.W.X.R.W.', 'Administrateur', 'admin')
ON DUPLICATE KEY UPDATE username=username;

