<?php

namespace App\DTO;

/**
 * Data Transfer Object pour les statistiques
 * Principe SOLID: Single Responsibility - transport de données uniquement
 */
class StatistiqueDTO
{
    private int $commandesEnCours;
    private int $commandesValidees;
    private float $recettesJournalieres;
    private int $commandesAnnulees;
    private array $topVentes;
    private array $dernieresCommandes;

    public function __construct(
        int $commandesEnCours,
        int $commandesValidees,
        float $recettesJournalieres,
        int $commandesAnnulees,
        array $topVentes,
        array $dernieresCommandes
    ) {
        $this->commandesEnCours = $commandesEnCours;
        $this->commandesValidees = $commandesValidees;
        $this->recettesJournalieres = $recettesJournalieres;
        $this->commandesAnnulees = $commandesAnnulees;
        $this->topVentes = $topVentes;
        $this->dernieresCommandes = $dernieresCommandes;
    }

    public function getCommandesEnCours(): int
    {
        return $this->commandesEnCours;
    }

    public function getCommandesValidees(): int
    {
        return $this->commandesValidees;
    }

    public function getRecettesJournalieres(): float
    {
        return $this->recettesJournalieres;
    }

    public function getCommandesAnnulees(): int
    {
        return $this->commandesAnnulees;
    }

    public function getTopVentes(): array
    {
        return $this->topVentes;
    }

    public function getDernieresCommandes(): array
    {
        return $this->dernieresCommandes;
    }
}