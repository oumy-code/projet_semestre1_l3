<?php

namespace App\Service;

use App\Service\Interface\AuthenticationServiceInterface;
use App\Repository\Interface\GestionnaireRepositoryInterface;
use App\Entity\Gestionnaire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Service d'authentification des gestionnaires
 */
class AuthenticationService implements AuthenticationServiceInterface
{
    private GestionnaireRepositoryInterface $gestionnaireRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        GestionnaireRepositoryInterface $gestionnaireRepository,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage
    ) {
        $this->gestionnaireRepository = $gestionnaireRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenStorage = $tokenStorage;
    }

    public function authenticate(string $login, string $password): ?Gestionnaire
    {
        $gestionnaire = $this->gestionnaireRepository->findByLogin($login);

        if (!$gestionnaire) {
            return null;
        }

        if (!$this->passwordHasher->isPasswordValid($gestionnaire, $password)) {
            return null;
        }

        return $gestionnaire;
    }

    public function isAuthenticated(): bool
    {
        $token = $this->tokenStorage->getToken();
        return $token !== null && $token->getUser() instanceof Gestionnaire;
    }

    public function getCurrentGestionnaire(): ?Gestionnaire
    {
        $token = $this->tokenStorage->getToken();
        
        if ($token === null) {
            return null;
        }

        $user = $token->getUser();
        
        return $user instanceof Gestionnaire ? $user : null;
    }

    public function logout(): void
    {
        $this->tokenStorage->setToken(null);
    }
}