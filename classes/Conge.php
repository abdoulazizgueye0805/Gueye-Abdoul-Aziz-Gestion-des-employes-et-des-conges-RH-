<?php

// Classe entite : represente UNE demande de conge en memoire.
class Conge
{
    private ?int $id;          // identifiant en base (null avant l'insertion)
    private int $employeId;    // id de l'employe concerne (cle etrangere)
    private string $type;      // type de conge (ex : "Congé payé", "Congé maladie"...)
    private string $dateDebut; // date de debut du conge (format AAAA-MM-JJ)
    private string $dateFin;   // date de fin du conge
    private string $statut;    // 'en_attente', 'valide' ou 'refuse'

    // Constructeur : le statut vaut 'en_attente' par defaut, car une nouvelle demande
    // de conge est toujours en attente de decision au moment de sa creation.
    public function __construct(
        int $employeId,
        string $type,
        string $dateDebut,
        string $dateFin,
        string $statut = 'en_attente',
        ?int $id = null
    ) {
        $this->employeId = $employeId;
        $this->type = $type;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->statut = $statut;
        $this->id = $id;
    }

    // --- Getters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeId(): int
    {
        return $this->employeId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    public function getDateFin(): string
    {
        return $this->dateFin;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    // --- Setters ---

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setEmployeId(int $employeId): void
    {
        $this->employeId = $employeId;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setDateDebut(string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin(string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}
