<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Employe.php';

class EmployeManager
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnexion();
    }

    public function ajouter(Employe $employe): bool
    {
        $requete = $this->pdo->prepare(
            "INSERT INTO employe (nom, poste, email, departement_id)
             VALUES (:nom, :poste, :email, :departement_id)"
        );

        return $requete->execute([
            'nom' => $employe->getNom(),
            'poste' => $employe->getPoste(),
            'email' => $employe->getEmail(),
            'departement_id' => $employe->getDepartementId(),
        ]);
    }

    public function lister(): array
    {
        $requete = $this->pdo->query("SELECT * FROM employe ORDER BY nom");
        $employes = [];

        foreach ($requete->fetchAll() as $ligne) {
            $employes[] = $this->hydrater($ligne);
        }

        return $employes;
    }

    public function rechercher(string $terme): array
    {
        $requete = $this->pdo->prepare(
            "SELECT * FROM employe
             WHERE nom LIKE :terme OR poste LIKE :terme OR email LIKE :terme
             ORDER BY nom"
        );
        $requete->execute(['terme' => '%' . $terme . '%']);

        $employes = [];
        foreach ($requete->fetchAll() as $ligne) {
            $employes[] = $this->hydrater($ligne);
        }

        return $employes;
    }

    public function trouverParId(int $id): ?Employe
    {
        $requete = $this->pdo->prepare("SELECT * FROM employe WHERE id_employe = :id");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch();

        return $ligne ? $this->hydrater($ligne) : null;
    }

    public function modifier(Employe $employe): bool
    {
        $requete = $this->pdo->prepare(
            "UPDATE employe
             SET nom = :nom, poste = :poste, email = :email, departement_id = :departement_id
             WHERE id_employe = :id"
        );

        return $requete->execute([
            'nom' => $employe->getNom(),
            'poste' => $employe->getPoste(),
            'email' => $employe->getEmail(),
            'departement_id' => $employe->getDepartementId(),
            'id' => $employe->getId(),
        ]);
    }

    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM employe WHERE id_employe = :id");

        return $requete->execute(['id' => $id]);
    }

    private function hydrater(array $ligne): Employe
    {
        return new Employe(
            $ligne['nom'],
            $ligne['poste'],
            $ligne['email'],
            (int) $ligne['departement_id'],
            (int) $ligne['id_employe']
        );
    }
}
