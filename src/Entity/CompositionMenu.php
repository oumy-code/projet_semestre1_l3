<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entité CompositionMenu - Lie les menus aux burgers et compléments
 */
#[ORM\Entity]
#[ORM\Table(name: 'composition_menu')]
class CompositionMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'compositions')]
    #[ORM\JoinColumn(nullable: false, name: 'id_menu')]
    private ?Menu $menu = null;

    #[ORM\ManyToOne(targetEntity: Burger::class)]
    #[ORM\JoinColumn(name: 'id_burger')]
    private ?Burger $burger = null;

    #[ORM\ManyToOne(targetEntity: Complement::class)]
    #[ORM\JoinColumn(name: 'id_complement')]
    private ?Complement $complement = null;

    #[ORM\Column(options: ['default' => 1])]
    private int $quantite = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;
        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;
        return $this;
    }

    public function getComplement(): ?Complement
    {
        return $this->complement;
    }

    public function setComplement(?Complement $complement): self
    {
        $this->complement = $complement;
        return $this;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
}