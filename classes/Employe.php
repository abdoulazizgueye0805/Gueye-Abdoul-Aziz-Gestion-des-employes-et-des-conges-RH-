<?php

class Employe
{
    private ?int $id;
    private string $nom;
    private string $poste;
    private string $email;
    private int $departementId;
    private int $soldeConges;

    public function __construct(
        string $nom,
        string $poste,
        string $email,
        int $departementId,
        int $soldeConges = 30,
        ?int $id = null
    ) {
        $this->nom = $nom;
        $this->poste = $poste;
        $this->email = $email;
        $this->departementId = $departementId;
        $this->soldeConges = $soldeConges;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPoste(): string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): void
    {
        $this->poste = $poste;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDepartementId(): int
    {
        return $this->departementId;
    }

    public function setDepartementId(int $departementId): void
    {
        $this->departementId = $departementId;
    }

    public function getSoldeConges(): int
    {
        return $this->soldeConges;
    }

    public function setSoldeConges(int $soldeConges): void
    {
        $this->soldeConges = $soldeConges;
    }
}
