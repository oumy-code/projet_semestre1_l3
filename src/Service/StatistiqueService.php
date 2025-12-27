<?php

namespace App\Service;

use App\Service\Interface\StatistiqueServiceInterface;
use App\Repository\Interface\StatistiqueRepositoryInterface;
use App\DTO\StatistiqueDTO;

/**
 * Service de calcul des statistiques
 * Principe SOLID: Single Responsibility
 */
class StatistiqueService implements StatistiqueServiceInterface
{
    private StatistiqueRepositoryInterface $statistiqueRepository;

    public function __construct(StatistiqueRepositoryInterface $statistiqueRepository)
    {
        $this->statistiqueRepository = $statistiqueRepository;
    }

    public function getStatistiquesDuJour(): StatistiqueDTO
    {
        return new StatistiqueDTO(
            $this->getCommandesEnCours(),
            $this->getCommandesValidees(),
            $this->getRecettesJournalieres(),
            $this->getCommandesAnnulees(),
            $this->getTopVentesDuJour(3),
            $this->getDernieresCommandes(5)
        );
    }

    public function getCommandesEnCours(): int
    {
        return $this->statistiqueRepository->countCommandesEnCoursDuJour();
    }

    public function getCommandesValidees(): int
    {
        return $this->statistiqueRepository->countCommandesValideesDuJour();
    }

    public function getRecettesJournalieres(): float
    {
        return $this->statistiqueRepository->sumRecettesDuJour();
    }

    public function getCommandesAnnulees(): int
    {
        return $this->statistiqueRepository->countCommandesAnnuleesDuJour();
    }

    public function getTopVentesDuJour(int $limit = 5): array
    {
        return $this->statistiqueRepository->findTopVentesDuJour($limit);
    }

    public function getDernieresCommandes(int $limit = 5): array
    {
        return $this->statistiqueRepository->findDernieresCommandes($limit);
    }
}