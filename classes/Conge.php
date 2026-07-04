<?php

class Conge
{
    private ?int $id;
    private int $employeId;
    private string $type;
    private string $dateDebut;
    private string $dateFin;
    private string $statut;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmployeId(): int
    {
        return $this->employeId;
    }

    public function setEmployeId(int $employeId): void
    {
        $this->employeId = $employeId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(): string
    {
        return $this->dateFin;
    }

    public function setDateFin(string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}
