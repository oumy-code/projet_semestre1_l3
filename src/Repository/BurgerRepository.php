<?php

namespace App\Repository;

use App\Entity\Burger;
use App\Repository\Interface\BurgerRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Burger>
 */
class BurgerRepository extends ServiceEntityRepository implements BurgerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    /**
     * Cette méthode reste utile pour l'affichage côté CLIENT (Front-office)
     * pour ne montrer que les produits disponibles.
     */
    public function findNotArchived(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.archive = :val')
            ->setParameter('val', false)
            ->orderBy('b.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function find(mixed $id, $lockMode = null, $lockVersion = null): ?Burger
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function save(Burger $burger, bool $flush = false): void
    {
        $this->getEntityManager()->persist($burger);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Burger $burger, bool $flush = false): void
    {
        $this->getEntityManager()->remove($burger);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Version corrigée pour la GESTION (Admin)
     * On retire le filtre d'archive pour que les burgers grisés restent visibles.
     */
    public function findPaginated(int $page, int $limit, ?string $searchTerm = null): Paginator
    {
        $query = $this->createQueryBuilder('b');

        // CONDITION DE RECHERCHE
        if ($searchTerm) {
            $query->andWhere('b.nom LIKE :search')
                  ->setParameter('search', '%' . $searchTerm . '%');
        }

        $query->orderBy('b.dateCreation', 'DESC')
              ->setFirstResult(($page - 1) * $limit)
              ->setMaxResults($limit);

        return new Paginator($query->getQuery());
    }
}