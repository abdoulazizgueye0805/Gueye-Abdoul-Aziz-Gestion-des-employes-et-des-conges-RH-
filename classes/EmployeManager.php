<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Employe.php';

// Classe gestionnaire : contient tout le CRUD pour la table "employe".
class EmployeManager
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnexion();
    }

    // AJOUTER : insere un nouvel employe en base.
    public function ajouter(Employe $employe): bool
    {
        // Requete preparee avec 4 espaces reserves (:nom, :poste, :email, :departement_id).
        $requete = $this->pdo->prepare(
            "INSERT INTO employe (nom, poste, email, departement_id)
             VALUES (:nom, :poste, :email, :departement_id)"
        );

        // On fournit les vraies valeurs, prises depuis l'objet Employe via ses getters.
        return $requete->execute([
            'nom' => $employe->getNom(),
            'poste' => $employe->getPoste(),
            'email' => $employe->getEmail(),
            'departement_id' => $employe->getDepartementId(),
        ]);
    }

    // LISTER : recupere tous les employes, tries par nom.
    public function lister(): array
    {
        $requete = $this->pdo->query("SELECT * FROM employe ORDER BY nom");
        $employes = [];

        // fetchAll() renvoie toutes les lignes ; on les transforme en objets Employe.
        foreach ($requete->fetchAll() as $ligne) {
            $employes[] = $this->hydrater($ligne);
        }

        return $employes;
    }

    // RECHERCHER : recupere les employes dont le nom, le poste OU l'email
    // contient le terme tape par l'utilisateur (fonctionnalite de recherche).
    public function rechercher(string $terme): array
    {
        // LIKE avec des % autour du terme = "contient ce texte, n'importe ou dans la chaine".
        $requete = $this->pdo->prepare(
            "SELECT * FROM employe
             WHERE nom LIKE :terme OR poste LIKE :terme OR email LIKE :terme
             ORDER BY nom"
        );
        // On ajoute nous-memes les % autour du terme avant de l'envoyer a la requete preparee
        // (donc toujours aucune injection SQL possible, la valeur reste "liee").
        $requete->execute(['terme' => '%' . $terme . '%']);

        $employes = [];
        foreach ($requete->fetchAll() as $ligne) {
            $employes[] = $this->hydrater($ligne);
        }

        return $employes;
    }

    // TROUVER PAR ID : recupere un seul employe.
    public function trouverParId(int $id): ?Employe
    {
        $requete = $this->pdo->prepare("SELECT * FROM employe WHERE id_employe = :id");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch();

        return $ligne ? $this->hydrater($ligne) : null;
    }

    // MODIFIER : met a jour les informations d'un employe existant.
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
            'id' => $employe->getId(), // identifie quelle ligne modifier
        ]);
    }

    // SUPPRIMER : supprime un employe grace a son id.
    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM employe WHERE id_employe = :id");

        return $requete->execute(['id' => $id]);
    }

    // Transforme une ligne brute de la base (tableau associatif) en objet Employe.
    // Methode privee : utilisee uniquement a l'interieur de cette classe.
    private function hydrater(array $ligne): Employe
    {
        return new Employe(
            $ligne['nom'],
            $ligne['poste'],
            $ligne['email'],
            (int) $ligne['departement_id'], // cast en int car MySQL peut renvoyer une chaine
            (int) $ligne['id_employe']
        );
    }
}
