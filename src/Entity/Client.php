<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Client - Hérite de User
 */
#[ORM\Entity]
#[ORM\Table(name: 'client')]
class Client extends User
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
}