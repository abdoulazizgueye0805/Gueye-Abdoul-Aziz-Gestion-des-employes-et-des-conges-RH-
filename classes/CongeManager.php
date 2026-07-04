<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Conge.php';

// Classe gestionnaire : contient tout le CRUD pour la table "conge",
// plus des methodes specifiques au metier (filtrer, valider, refuser).
class CongeManager
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnexion();
    }

    // AJOUTER : insere une nouvelle demande de conge (statut 'en_attente' par defaut).
    public function ajouter(Conge $conge): bool
    {
        $requete = $this->pdo->prepare(
            "INSERT INTO conge (employe_id, type, date_debut, date_fin, statut)
             VALUES (:employe_id, :type, :date_debut, :date_fin, :statut)"
        );

        return $requete->execute([
            'employe_id' => $conge->getEmployeId(),
            'type' => $conge->getType(),
            'date_debut' => $conge->getDateDebut(),
            'date_fin' => $conge->getDateFin(),
            'statut' => $conge->getStatut(),
        ]);
    }

    // LISTER : recupere tous les conges, les plus recents (date_debut) en premier.
    public function lister(): array
    {
        $requete = $this->pdo->query("SELECT * FROM conge ORDER BY date_debut DESC");

        return $this->hydraterTous($requete->fetchAll());
    }

    // TROUVER PAR ID : recupere une seule demande de conge.
    public function trouverParId(int $id): ?Conge
    {
        $requete = $this->pdo->prepare("SELECT * FROM conge WHERE id_conge = :id");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch();

        return $ligne ? $this->hydrater($ligne) : null;
    }

    // Recupere tous les conges d'UN employe en particulier (filtre par employe).
    public function listerParEmploye(int $employeId): array
    {
        $requete = $this->pdo->prepare(
            "SELECT * FROM conge WHERE employe_id = :employe_id ORDER BY date_debut DESC"
        );
        $requete->execute(['employe_id' => $employeId]);

        return $this->hydraterTous($requete->fetchAll());
    }

    // Recupere tous les conges ayant un statut precis (filtre par statut :
    // 'en_attente', 'valide' ou 'refuse').
    public function listerParStatut(string $statut): array
    {
        $requete = $this->pdo->prepare(
            "SELECT * FROM conge WHERE statut = :statut ORDER BY date_debut DESC"
        );
        $requete->execute(['statut' => $statut]);

        return $this->hydraterTous($requete->fetchAll());
    }

    // MODIFIER : met a jour toutes les informations d'une demande de conge existante.
    public function modifier(Conge $conge): bool
    {
        $requete = $this->pdo->prepare(
            "UPDATE conge
             SET employe_id = :employe_id, type = :type, date_debut = :date_debut,
                 date_fin = :date_fin, statut = :statut
             WHERE id_conge = :id"
        );

        return $requete->execute([
            'employe_id' => $conge->getEmployeId(),
            'type' => $conge->getType(),
            'date_debut' => $conge->getDateDebut(),
            'date_fin' => $conge->getDateFin(),
            'statut' => $conge->getStatut(),
            'id' => $conge->getId(),
        ]);
    }

    // Change uniquement le statut d'un conge (utilise pour "Valider" ou "Refuser"
    // sans avoir a renvoyer toutes les autres informations du conge).
    public function changerStatut(int $id, string $statut): bool
    {
        $requete = $this->pdo->prepare("UPDATE conge SET statut = :statut WHERE id_conge = :id");

        return $requete->execute(['statut' => $statut, 'id' => $id]);
    }

    // SUPPRIMER : supprime une demande de conge grace a son id.
    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM conge WHERE id_conge = :id");

        return $requete->execute(['id' => $id]);
    }

    // Transforme UNE ligne brute de la base en objet Conge.
    private function hydrater(array $ligne): Conge
    {
        return new Conge(
            (int) $ligne['employe_id'],
            $ligne['type'],
            $ligne['date_debut'],
            $ligne['date_fin'],
            $ligne['statut'],
            (int) $ligne['id_conge']
        );
    }

    // Transforme PLUSIEURS lignes brutes en un tableau d'objets Conge.
    // Factorise le code commun a lister(), listerParEmploye() et listerParStatut().
    private function hydraterTous(array $lignes): array
    {
        $conges = [];

        foreach ($lignes as $ligne) {
            $conges[] = $this->hydrater($ligne);
        }

        return $conges;
    }
}
