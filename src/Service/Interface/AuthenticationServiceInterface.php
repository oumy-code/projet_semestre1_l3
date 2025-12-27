<?php

namespace App\Service\Interface;

use App\Entity\Gestionnaire;

/**
 * Interface pour l'authentification des gestionnaires
 */
interface AuthenticationServiceInterface
{
    /**
     * Authentifie un gestionnaire
     * 
     * @param string $login
     * @param string $password
     * @return Gestionnaire|null
     */
    public function authenticate(string $login, string $password): ?Gestionnaire;

    /**
     * Vérifie si un gestionnaire est connecté
     * 
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * Récupère le gestionnaire connecté
     * 
     * @return Gestionnaire|null
     */
    public function getCurrentGestionnaire(): ?Gestionnaire;

    /**
     * Déconnecte le gestionnaire
     * 
     * @return void
     */
    public function logout(): void;
}