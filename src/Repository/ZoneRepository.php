<?php

namespace App\Repository;

use App\Repository\Interface\ZoneRepositoryInterface;
use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les zones de livraison
 */
class ZoneRepository extends ServiceEntityRepository implements ZoneRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    public function findByNom(string $nom): ?Zone
    {
        return $this->createQueryBuilder('z')
            ->where('z.nom = :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Zone $zone): void
    {
        $this->getEntityManager()->persist($zone);
        $this->getEntityManager()->flush();
    }

    public function remove(Zone $zone): void
    {
        $this->getEntityManager()->remove($zone);
        $this->getEntityManager()->flush();
    }
}