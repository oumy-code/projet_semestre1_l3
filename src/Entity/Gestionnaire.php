<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entité Gestionnaire - Hérite de User
 */
#[ORM\Entity]
#[ORM\Table(name: 'gestionnaire')]
class Gestionnaire extends User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 50, unique: true)]
    private ?string $login = null;

    #[ORM\Column(length: 255, name: 'mot_de_passe')]
    private ?string $motDePasse = null;

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }

    // Implémentation de UserInterface
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    public function getRoles(): array
    {
        return ['ROLE_GESTIONNAIRE', 'ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporaires, nettoyez-les ici
    }

    // Implémentation de PasswordAuthenticatedUserInterface
    public function getPassword(): ?string
    {
        return $this->motDePasse;
    }
}