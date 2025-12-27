<?php

namespace App\Repository;

use App\Repository\Interface\CommandeRepositoryInterface;
use App\DTO\CommandeFilterDTO;
use App\Entity\Commande;
use App\Enum\EtatCommande;
use App\Enum\TypeRecuperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator; // <--- IMPORTANT

class CommandeRepository extends ServiceEntityRepository implements CommandeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findWithFilters(?CommandeFilterDTO $filters, int $page = 1, int $limit = 6): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.client', 'client')
            ->addSelect('client') 
            ->orderBy('c.dateCommande', 'DESC');

        // --- GESTION DES FILTRES ---
        if ($filters) {
            if ($filters->getEtat()) {
                $qb->andWhere('c.etat = :etat')
                   ->setParameter('etat', $filters->getEtat());
            }

            if ($filters->getTypeRecuperation()) {
                $qb->andWhere('c.typeRecuperation = :type')
                   ->setParameter('type', $filters->getTypeRecuperation());
            }

            if ($filters->getDateDebut()) {
                $qb->andWhere('c.dateCommande >= :dateDebut')
                   ->setParameter('dateDebut', $filters->getDateDebut());
            }

            if ($filters->getDateFin()) {
                $qb->andWhere('c.dateCommande <= :dateFin')
                   ->setParameter('dateFin', $filters->getDateFin());
            }

            if ($filters->getClientNom()) {
                $qb->andWhere('client.nom LIKE :nom OR client.prenom LIKE :nom')
                   ->setParameter('nom', '%' . $filters->getClientNom() . '%');
            }
        }

        // --- GESTION DE LA PAGINATION ---
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        // On utilise le Paginator pour gérer correctement les jointures OneToMany
        $paginator = new Paginator($qb->getQuery());
        
        // On retourne un tableau simple pour que le Service n'ait pas à être modifié
        return iterator_to_array($paginator->getIterator());
    }

    public function findCommandesALivrerParZone(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.zone', 'z')
            ->addSelect('z')
            ->where('c.typeRecuperation = :livraison')
            ->andWhere('c.etat = :etat')
            ->andWhere('c.livreur IS NULL')
            ->setParameter('livraison', TypeRecuperation::LIVRAISON)
            ->setParameter('etat', EtatCommande::EN_COURS)
            ->orderBy('z.nom', 'ASC')
            ->addOrderBy('c.dateCommande', 'ASC');

        $commandes = $qb->getQuery()->getResult();

        $grouped = [];
        foreach ($commandes as $commande) {
            $zoneNom = $commande->getZone() ? $commande->getZone()->getNom() : 'Sans zone';
            if (!isset($grouped[$zoneNom])) {
                $grouped[$zoneNom] = [];
            }
            $grouped[$zoneNom][] = $commande;
        }

        return $grouped;
    }

    public function save(Commande $commande): void
    {
        $this->getEntityManager()->persist($commande);
        $this->getEntityManager()->flush();
    }

    public function remove(Commande $commande): void
    {
        $this->getEntityManager()->remove($commande);
        $this->getEntityManager()->flush();
    }
}