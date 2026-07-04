<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Departement.php';

class DepartementManager
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnexion();
    }

    public function ajouter(Departement $departement): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO departement (nom) VALUES (:nom)");

        return $requete->execute(['nom' => $departement->getNom()]);
    }

    public function lister(): array
    {
        $requete = $this->pdo->query("SELECT * FROM departement ORDER BY nom");
        $departements = [];

        foreach ($requete->fetchAll() as $ligne) {
            $departements[] = $this->hydrater($ligne);
        }

        return $departements;
    }

    public function trouverParId(int $id): ?Departement
    {
        $requete = $this->pdo->prepare("SELECT * FROM departement WHERE id_departement = :id");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch();

        return $ligne ? $this->hydrater($ligne) : null;
    }

    public function modifier(Departement $departement): bool
    {
        $requete = $this->pdo->prepare(
            "UPDATE departement SET nom = :nom WHERE id_departement = :id"
        );

        return $requete->execute([
            'nom' => $departement->getNom(),
            'id' => $departement->getId(),
        ]);
    }

    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM departement WHERE id_departement = :id");

        return $requete->execute(['id' => $id]);
    }

    private function hydrater(array $ligne): Departement
    {
        return new Departement($ligne['nom'], (int) $ligne['id_departement']);
    }
}
