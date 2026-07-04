<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Departement.php';

// Classe gestionnaire (Manager) : c'est elle qui sait parler a la table "departement"
// en base de donnees. Elle transforme les lignes SQL en objets Departement et inversement.
class DepartementManager
{
    // On garde la connexion PDO pour l'utiliser dans toutes les methodes ci-dessous.
    private PDO $pdo;

    // Le constructeur recoit un objet Database et recupere sa connexion PDO une seule fois.
    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnexion();
    }

    // AJOUTER : insere un nouveau departement en base.
    public function ajouter(Departement $departement): bool
    {
        // Requete preparee : ":nom" est un "espace reserve" qui sera rempli plus bas.
        // PDO echappe automatiquement la valeur -> aucune injection SQL possible.
        $requete = $this->pdo->prepare("INSERT INTO departement (nom) VALUES (:nom)");

        // On execute la requete en fournissant la vraie valeur pour :nom.
        return $requete->execute(['nom' => $departement->getNom()]);
    }

    // LISTER : recupere tous les departements de la base, tries par nom.
    public function lister(): array
    {
        $requete = $this->pdo->query("SELECT * FROM departement ORDER BY nom");
        $departements = [];

        // On transforme chaque ligne brute (tableau) renvoyee par MySQL
        // en un vrai objet Departement grace a hydrater().
        foreach ($requete->fetchAll() as $ligne) {
            $departements[] = $this->hydrater($ligne);
        }

        return $departements;
    }

    // TROUVER PAR ID : recupere un seul departement grace a son identifiant.
    public function trouverParId(int $id): ?Departement
    {
        $requete = $this->pdo->prepare("SELECT * FROM departement WHERE id_departement = :id");
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch(); // fetch() = une seule ligne (ou false si rien trouve)

        // Si une ligne existe, on la transforme en objet ; sinon on renvoie null.
        return $ligne ? $this->hydrater($ligne) : null;
    }

    // MODIFIER : met a jour le nom d'un departement existant (identifie par son id).
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

    // SUPPRIMER : supprime un departement grace a son id.
    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM departement WHERE id_departement = :id");

        return $requete->execute(['id' => $id]);
    }

    // Methode privee (utilisee seulement a l'interieur de cette classe) :
    // transforme une ligne brute de la base de donnees (tableau associatif)
    // en un objet Departement pret a etre utilise dans le reste du code.
    private function hydrater(array $ligne): Departement
    {
        return new Departement($ligne['nom'], (int) $ligne['id_departement']);
    }
}
