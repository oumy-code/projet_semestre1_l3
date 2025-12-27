<?php

namespace App\Repository\Interface;

use App\Entity\Gestionnaire;

/**
 * Interface pour le repository des gestionnaires
 */
interface GestionnaireRepositoryInterface
{
    /**
     * Note : Les méthodes find() et findAll() ont été retirées 
     * pour assurer la compatibilité avec PHP 8.4 et Doctrine.
     */

    /**
     * Trouve un gestionnaire par son login
     */
    public function findByLogin(string $login): ?Gestionnaire;

    /**
     * Sauvegarde un gestionnaire
     */
    public function save(Gestionnaire $gestionnaire): void;

    /**
     * Supprime un gestionnaire
     */
    public function remove(Gestionnaire $gestionnaire): void;
}