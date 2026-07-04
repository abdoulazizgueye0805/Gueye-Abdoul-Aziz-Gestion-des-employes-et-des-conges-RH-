-- Script de creation de la base de donnees
-- Projet : Gestion des employes et des conges (RH)

-- Cree la base si elle n'existe pas deja, en UTF-8 (pour bien gerer les accents francais).
CREATE DATABASE IF NOT EXISTS gestion_rh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_rh;

-- Table des departements : la plus simple, juste un identifiant et un nom.
CREATE TABLE IF NOT EXISTS departement (
    id_departement INT AUTO_INCREMENT PRIMARY KEY, -- identifiant unique, auto-incremente
    nom VARCHAR(100) NOT NULL
);

-- Table des employes.
CREATE TABLE IF NOT EXISTS employe (
    id_employe INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    poste VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE, -- UNIQUE : deux employes ne peuvent pas avoir le meme email
    departement_id INT NOT NULL,        -- cle etrangere vers departement
    -- Cette contrainte garantit qu'un employe est toujours rattache
    -- a un departement qui existe reellement dans la table departement.
    CONSTRAINT fk_employe_departement
        FOREIGN KEY (departement_id) REFERENCES departement(id_departement)
        -- ON DELETE RESTRICT : interdit de supprimer un departement
        -- tant qu'il contient encore des employes.
        ON DELETE RESTRICT
);

-- Table des conges (demandes de congé).
CREATE TABLE IF NOT EXISTS conge (
    id_conge INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT NOT NULL,     -- cle etrangere vers employe
    type VARCHAR(50) NOT NULL,   -- ex : "Congé payé", "Congé maladie"...
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    -- ENUM : le statut ne peut prendre QUE l'une de ces 3 valeurs, rien d'autre.
    -- 'en_attente' par defaut : toute nouvelle demande commence non traitee.
    statut ENUM('en_attente', 'valide', 'refuse') NOT NULL DEFAULT 'en_attente',
    CONSTRAINT fk_conge_employe
        FOREIGN KEY (employe_id) REFERENCES employe(id_employe)
        -- ON DELETE CASCADE : si un employe est supprime, ses demandes
        -- de conge sont automatiquement supprimees avec lui (pas de donnees orphelines).
        ON DELETE CASCADE
);
