<?php

// Classe entite : represente UN departement en memoire (dans le code PHP).
// Elle ne parle pas a la base de donnees elle-meme, c'est le role de DepartementManager.
class Departement
{
    // Attributs prives : on ne peut pas faire "$departement->nom = ..." depuis l'exterieur,
    // il faut obligatoirement passer par les getters/setters ci-dessous (encapsulation).
    private ?int $id;   // identifiant en base (null tant que l'objet n'est pas encore enregistre)
    private string $nom; // nom du departement

    // Le constructeur : appele avec "new Departement(...)".
    // $id est optionnel (valeur par defaut null) car un nouveau departement n'a pas encore d'id
    // avant d'etre inséré en base (c'est MySQL qui l'attribue avec AUTO_INCREMENT).
    public function __construct(string $nom, ?int $id = null)
    {
        $this->nom = $nom;
        $this->id = $id;
    }

    // Getter : permet de LIRE l'id depuis l'exterieur de la classe.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Setter : permet de MODIFIER l'id depuis l'exterieur, de facon controlee.
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    // Getter du nom.
    public function getNom(): string
    {
        return $this->nom;
    }

    // Setter du nom.
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
}
