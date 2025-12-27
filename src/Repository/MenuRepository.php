<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Repository\Interface\MenuRepositoryInterface;
use Doctrine\ORM\Query; // Import indispensable
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository implements MenuRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * Cette méthode doit impérativement retourner le type : Query
     */
    public function getActiveMenusQuery(): Query
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.compositions', 'c')
            ->addSelect('c')
            ->leftJoin('c.burger', 'b')
            ->addSelect('b')
            ->leftJoin('c.complement', 'comp')
            ->addSelect('comp')
            ->where('m.archive = :archived')
            ->setParameter('archived', false)
            ->orderBy('m.dateCreation', 'DESC')
            ->getQuery(); 
    }

    public function findAllActiveWithAssociations(): array
    {
        return $this->getActiveMenusQuery()->getResult();
    }

    public function findByName(string $search): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.nom LIKE :search')
            ->andWhere('m.archive = :archived')
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('archived', false)
            ->getQuery()
            ->getResult();
    }

    public function save(Menu $menu, bool $flush = false): void
    {
        $this->getEntityManager()->persist($menu);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Menu $menu, bool $flush = false): void
    {
        $this->getEntityManager()->remove($menu);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}