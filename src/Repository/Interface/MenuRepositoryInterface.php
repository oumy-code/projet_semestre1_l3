<?php
// src/Repository/MenuRepositoryInterface.php

namespace App\Repository\Interface;

use App\Entity\Menu;
use Doctrine\ORM\Query;

interface MenuRepositoryInterface
{
    public function getActiveMenusQuery(): Query; // Ajoute cette ligne
    public function findAllActiveWithAssociations(): array;
    public function findByName(string $search): array;
    public function save(Menu $menu, bool $flush = false): void;
    public function remove(Menu $menu, bool $flush = false): void;
}