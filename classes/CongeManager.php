<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Conge.php';

class CongeManager
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnexion();
    }

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

    public function lister(): array
    {
        $requete = $this->pdo->query("SELECT * FROM conge ORDER BY date_debut DESC");

        return $this->hydraterTous($requete->fetchAll());
    }

    public function trouverParId(int $id): ?Conge
    {
        $requete = $this->pdo->prepare("SELECT * FROM conge WHERE id_conge = :id");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch();

        return $ligne ? $this->hydrater($ligne) : null;
    }

    public function listerParEmploye(int $employeId): array
    {
        $requete = $this->pdo->prepare(
            "SELECT * FROM conge WHERE employe_id = :employe_id ORDER BY date_debut DESC"
        );
        $requete->execute(['employe_id' => $employeId]);

        return $this->hydraterTous($requete->fetchAll());
    }

    public function listerParStatut(string $statut): array
    {
        $requete = $this->pdo->prepare(
            "SELECT * FROM conge WHERE statut = :statut ORDER BY date_debut DESC"
        );
        $requete->execute(['statut' => $statut]);

        return $this->hydraterTous($requete->fetchAll());
    }

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

    public function changerStatut(int $id, string $statut): bool
    {
        $requete = $this->pdo->prepare("UPDATE conge SET statut = :statut WHERE id_conge = :id");

        return $requete->execute(['statut' => $statut, 'id' => $id]);
    }

    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM conge WHERE id_conge = :id");

        return $requete->execute(['id' => $id]);
    }

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

    private function hydraterTous(array $lignes): array
    {
        $conges = [];

        foreach ($lignes as $ligne) {
            $conges[] = $this->hydrater($ligne);
        }

        return $conges;
    }
}
