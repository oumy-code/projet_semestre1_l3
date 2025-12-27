<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Livreur - Hérite de User
 */
#[ORM\Entity]
#[ORM\Table(name: 'livreur')]
class Livreur extends User
{
    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $disponible = true;

    public function isDisponible(): bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;
        return $this;
    }
}