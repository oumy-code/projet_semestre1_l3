<?php

namespace App\Repository\Interface;

use App\DTO\CommandeFilterDTO;
use App\Entity\Commande;

interface CommandeRepositoryInterface
{
    /**
     * Trouve les commandes avec filtres et pagination
     */
    public function findWithFilters(?CommandeFilterDTO $filters, int $page = 1, int $limit = 6): array;

    /**
     * Trouve les commandes à livrer groupées par zone
     */
    public function findCommandesALivrerParZone(): array;

    public function save(Commande $commande): void;

    public function remove(Commande $commande): void;
}