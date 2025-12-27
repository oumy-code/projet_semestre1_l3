<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Ligne de Commande
 */
#[ORM\Entity]
#[ORM\Table(name: 'ligne_commande')]
class LigneCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'lignesCommande')]
    #[ORM\JoinColumn(nullable: false, name: 'id_commande')]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(targetEntity: Burger::class)]
    #[ORM\JoinColumn(name: 'id_burger')]
    private ?Burger $burger = null;

    #[ORM\ManyToOne(targetEntity: Menu::class)]
    #[ORM\JoinColumn(name: 'id_menu')]
    private ?Menu $menu = null;

    #[ORM\ManyToOne(targetEntity: Complement::class)]
    #[ORM\JoinColumn(name: 'id_complement')]
    private ?Complement $complement = null;

    #[ORM\Column(options: ['default' => 1])]
    private int $quantite = 1;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, name: 'prix_unitaire')]
    private ?string $prixUnitaire = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, name: 'sous_total')]
    private ?string $sousTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;
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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;
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
        $this->calculerSousTotal();
        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;
        $this->calculerSousTotal();
        return $this;
    }

    public function getSousTotal(): ?string
    {
        return $this->sousTotal;
    }

    public function setSousTotal(string $sousTotal): self
    {
        $this->sousTotal = $sousTotal;
        return $this;
    }

    /**
     * Calcule automatiquement le sous-total
     */
    private function calculerSousTotal(): void
    {
        if ($this->prixUnitaire !== null) {
            $this->sousTotal = (string) ((float) $this->prixUnitaire * $this->quantite);
        }
    }

    /**
     * Retourne le nom du produit
     */
    public function getNomProduit(): string
    {
        if ($this->burger) {
            return $this->burger->getNom();
        }
        if ($this->menu) {
            return $this->menu->getNom();
        }
        if ($this->complement) {
            return $this->complement->getNom();
        }
        return 'Produit inconnu';
    }
}