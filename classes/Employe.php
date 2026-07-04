<?php

// Classe entite : represente UN employe en memoire.
// Comme pour Departement, elle ne contient aucune logique SQL : c'est EmployeManager qui s'en charge.
class Employe
{
    // Tous les attributs sont prives (encapsulation) : on doit passer par les getters/setters.
    private ?int $id;            // identifiant en base (null avant l'insertion)
    private string $nom;         // nom de l'employe
    private string $poste;       // poste occupe (ex : "Comptable")
    private string $email;       // email de l'employe
    private int $departementId;  // id du departement auquel il appartient (cle etrangere)

    // Constructeur : nom, poste, email et departementId sont obligatoires.
    // $id est optionnel car un nouvel employe n'a pas encore d'id avant l'insertion en base.
    public function __construct(
        string $nom,
        string $poste,
        string $email,
        int $departementId,
        ?int $id = null
    ) {
        $this->nom = $nom;
        $this->poste = $poste;
        $this->email = $email;
        $this->departementId = $departementId;
        $this->id = $id;
    }

    // --- Getters : lecture des attributs prives depuis l'exterieur ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPoste(): string
    {
        return $this->poste;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDepartementId(): int
    {
        return $this->departementId;
    }

    // --- Setters : modification controlee des attributs prives ---

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPoste(string $poste): void
    {
        $this->poste = $poste;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setDepartementId(int $departementId): void
    {
        $this->departementId = $departementId;
    }
}
