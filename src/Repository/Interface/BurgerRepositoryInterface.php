<?php

namespace App\Repository\Interface;

use App\Entity\Burger;

use Doctrine\ORM\Tools\Pagination\Paginator;
interface BurgerRepositoryInterface
{
    /**
     * @return Burger[]
     */
    public function findNotArchived(): array;

    // Signature compatible avec Doctrine
    public function find(mixed $id, $lockMode = null, $lockVersion = null): ?Burger;

    public function save(Burger $burger, bool $flush = false): void;

    public function remove(Burger $burger, bool $flush = false): void;
   public function findPaginated(int $page, int $limit, ?string $searchTerm = null): Paginator;
}