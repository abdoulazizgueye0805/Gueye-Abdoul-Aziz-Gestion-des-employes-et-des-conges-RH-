-- Script de creation de la base de donnees
-- Projet : Gestion des employes et des conges (RH)

CREATE DATABASE IF NOT EXISTS gestion_rh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_rh;

-- Table des departements
CREATE TABLE IF NOT EXISTS departement (
    id_departement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table des employes
CREATE TABLE IF NOT EXISTS employe (
    id_employe INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    poste VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    departement_id INT NOT NULL,
    solde_conges INT NOT NULL DEFAULT 30,
    CONSTRAINT fk_employe_departement
        FOREIGN KEY (departement_id) REFERENCES departement(id_departement)
        ON DELETE RESTRICT
);

-- Table des conges
CREATE TABLE IF NOT EXISTS conge (
    id_conge INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('en_attente', 'valide', 'refuse') NOT NULL DEFAULT 'en_attente',
    CONSTRAINT fk_conge_employe
        FOREIGN KEY (employe_id) REFERENCES employe(id_employe)
        ON DELETE CASCADE
);
