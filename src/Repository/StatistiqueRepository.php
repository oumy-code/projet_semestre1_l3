<?php

namespace App\Repository;

use App\Repository\Interface\StatistiqueRepositoryInterface;
use App\Enum\EtatCommande;
use Doctrine\ORM\EntityManagerInterface;

class StatistiqueRepository implements StatistiqueRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Helper pour obtenir les bornes de la journée actuelle
     */
    private function getTodayRange(): array
    {
        return [
            'debut' => new \DateTime('today 00:00:00'),
            'fin' => new \DateTime('today 23:59:59')
        ];
    }

    public function countCommandesEnCoursDuJour(): int
    {
        $range = $this->getTodayRange();
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(c.id)
             FROM App\Entity\Commande c
             WHERE c.etat = :etat
             AND c.dateCommande BETWEEN :debut AND :fin'
        );
        $query->setParameters(array_merge(['etat' => EtatCommande::EN_COURS], $range));

        return (int) $query->getSingleScalarResult();
    }

    public function countCommandesValideesDuJour(): int
    {
        $range = $this->getTodayRange();
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(c.id)
             FROM App\Entity\Commande c
             WHERE c.etat = :etat
             AND c.dateCommande BETWEEN :debut AND :fin'
        );
        $query->setParameters(array_merge(['etat' => EtatCommande::TERMINE], $range));

        return (int) $query->getSingleScalarResult();
    }

    public function sumRecettesDuJour(): float
    {
        $range = $this->getTodayRange();
        $query = $this->entityManager->createQuery(
            'SELECT COALESCE(SUM(c.montantTotal), 0)
             FROM App\Entity\Commande c
             WHERE c.etat = :etat
             AND c.dateCommande BETWEEN :debut AND :fin'
        );
        $query->setParameters(array_merge(['etat' => EtatCommande::TERMINE], $range));

        return (float) $query->getSingleScalarResult();
    }

    public function countCommandesAnnuleesDuJour(): int
    {
        $range = $this->getTodayRange();
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(c.id)
             FROM App\Entity\Commande c
             WHERE c.etat = :etat
             AND c.dateCommande BETWEEN :debut AND :fin'
        );
        $query->setParameters(array_merge(['etat' => EtatCommande::ANNULE], $range));

        return (int) $query->getSingleScalarResult();
    }

    public function findTopVentesDuJour(int $limit): array
    {
        $range = $this->getTodayRange();
        $params = array_merge(['annule' => EtatCommande::ANNULE], $range);

        // Top Burgers
        $queryBurgers = $this->entityManager->createQuery(
            'SELECT b.nom as nom, SUM(lc.quantite) as quantite, \'burger\' as type
             FROM App\Entity\LigneCommande lc
             JOIN lc.burger b
             JOIN lc.commande c
             WHERE c.dateCommande BETWEEN :debut AND :fin
             AND c.etat != :annule
             GROUP BY b.id, b.nom
             ORDER BY quantite DESC'
        )->setParameters($params)->setMaxResults($limit);
        
        $burgers = $queryBurgers->getResult();

        // Top Menus
        $queryMenus = $this->entityManager->createQuery(
            'SELECT m.nom as nom, SUM(lc.quantite) as quantite, \'menu\' as type
             FROM App\Entity\LigneCommande lc
             JOIN lc.menu m
             JOIN lc.commande c
             WHERE c.dateCommande BETWEEN :debut AND :fin
             AND c.etat != :annule
             GROUP BY m.id, m.nom
             ORDER BY quantite DESC'
        )->setParameters($params)->setMaxResults($limit);

        $menus = $queryMenus->getResult();

        $results = array_merge($burgers, $menus);
        usort($results, fn($a, $b) => $b['quantite'] <=> $a['quantite']);

        return array_slice($results, 0, $limit);
    }

    public function findDernieresCommandes(int $limit): array
    {
        $range = $this->getTodayRange();
        $query = $this->entityManager->createQuery(
            'SELECT c
             FROM App\Entity\Commande c
             WHERE c.dateCommande BETWEEN :debut AND :fin
             ORDER BY c.dateCommande DESC'
        )->setParameters($range)->setMaxResults($limit);

        return $query->getResult();
    }
}