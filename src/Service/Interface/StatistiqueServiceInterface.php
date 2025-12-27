<?php

namespace App\Service\Interface;

use App\DTO\StatistiqueDTO;

/**
 * Interface pour les statistiques du dashboard
 * Principe SOLID: Interface Segregation Principle
 */
interface StatistiqueServiceInterface
{
    /**
     * Récupère toutes les statistiques du jour
     * 
     * @return StatistiqueDTO
     */
    public function getStatistiquesDuJour(): StatistiqueDTO;

    /**
     * Nombre de commandes en cours
     * 
     * @return int
     */
    public function getCommandesEnCours(): int;

    /**
     * Nombre de commandes validées (terminées)
     * 
     * @return int
     */
    public function getCommandesValidees(): int;

    /**
     * Recettes journalières
     * 
     * @return float
     */
    public function getRecettesJournalieres(): float;

    /**
     * Nombre de commandes annulées
     * 
     * @return int
     */
    public function getCommandesAnnulees(): int;

    /**
     * Top des ventes (burgers et menus) du jour
     * 
     * @param int $limit
     * @return array
     */
    public function getTopVentesDuJour(int $limit = 5): array;

    /**
     * Dernières commandes
     * 
     * @param int $limit
     * @return array
     */
    public function getDernieresCommandes(int $limit = 5): array;
}