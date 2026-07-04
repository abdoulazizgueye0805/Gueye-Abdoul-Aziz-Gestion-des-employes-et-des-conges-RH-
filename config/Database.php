<?php

// Cette classe centralise la connexion a la base de donnees.
// Toutes les classes "Manager" (EmployeManager, DepartementManager, CongeManager)
// passeront par ici pour obtenir un objet PDO, au lieu de se reconnecter chacune de leur cote.
class Database
{
    // Attributs prives : personne en dehors de cette classe ne peut lire ou modifier
    // ces informations de connexion directement (encapsulation).
    private string $host = "localhost";      // adresse du serveur MySQL (ici en local)
    private string $dbname = "gestion_rh";   // nom de la base de donnees a utiliser
    private string $user = "root";           // utilisateur MySQL
    private string $password = "";           // mot de passe MySQL (vide par defaut sous XAMPP)

    // On stocke la connexion une fois creee, pour ne pas se reconnecter a chaque appel.
    // Elle vaut "null" tant qu'aucune connexion n'a encore ete etablie.
    private ?PDO $connexion = null;

    // Methode publique qui renvoie la connexion PDO active.
    // C'est la seule porte d'entree pour parler a la base de donnees.
    public function getConnexion(): PDO
    {
        // Si la connexion n'existe pas encore, on la cree maintenant.
        if ($this->connexion === null) {
            // Le DSN (Data Source Name) decrit a PDO comment se connecter :
            // quel type de base (mysql), quel serveur (host), quelle base (dbname), quel encodage.
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";

            // Creation de l'objet PDO avec les identifiants et quelques options :
            $this->connexion = new PDO($dsn, $this->user, $this->password, [
                // Si une requete echoue, PDO lance une exception au lieu d'echouer silencieusement.
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Les resultats de SELECT seront retournes sous forme de tableaux associatifs
                // (cle = nom de colonne), plus simple a manipuler.
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        // On retourne la connexion (nouvellement creee ou deja existante).
        return $this->connexion;
    }
}
