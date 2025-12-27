<?php

namespace App\Repository\Interface;

use App\Entity\Zone;

/**
 * Interface pour le repository des zones
 */
interface ZoneRepositoryInterface
{
    /**
     * Note : find() et findAll() sont retirés pour la compatibilité PHP 8.4.
     * Ils restent accessibles via l'héritage de ServiceEntityRepository.
     */

    /**
     * Trouve une zone par son nom
     * * @param string $nom
     * @return Zone|null
     */
    public function findByNom(string $nom): ?Zone;

    /**
     * Sauvegarde une zone
     * * @param Zone $zone
     * @return void
     */
    public function save(Zone $zone): void;

    /**
     * Supprime une zone
     * * @param Zone $zone
     * @return void
     */
    public function remove(Zone $zone): void;
}