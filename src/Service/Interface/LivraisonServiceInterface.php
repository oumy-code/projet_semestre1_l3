<?php

namespace App\Service\Interface;

use App\Entity\Livreur;

/**
 * Interface pour la gestion des livraisons
 */
interface LivraisonServiceInterface
{
    /**
     * Affecte un livreur à plusieurs commandes
     * 
     * @param array $commandeIds IDs des commandes
     * @param Livreur $livreur
     * @return bool
     */
    public function affecterLivreur(array $commandeIds, Livreur $livreur): bool;

    /**
     * Libère un livreur (remet disponible = true)
     * 
     * @param int $livreurId
     * @return bool
     */
    public function libererLivreur(int $livreurId): bool;

    /**
     * Récupère les livreurs disponibles
     * 
     * @return array
     */
    public function getLivreursDisponibles(): array;
}