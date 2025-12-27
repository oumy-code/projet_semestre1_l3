<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Zone de livraison
 */
#[ORM\Entity]
#[ORM\Table(name: 'zone')]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nom = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, name: 'prix_livraison')]
    private ?string $prixLivraison = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $quartiers = null;

    #[ORM\Column(type: 'datetime', name: 'date_creation')]
    private ?\DateTimeInterface $dateCreation = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrixLivraison(): ?string
    {
        return $this->prixLivraison;
    }

    public function setPrixLivraison(string $prixLivraison): self
    {
        $this->prixLivraison = $prixLivraison;
        return $this;
    }

    public function getQuartiers(): ?string
    {
        return $this->quartiers;
    }

    public function setQuartiers(?string $quartiers): self
    {
        $this->quartiers = $quartiers;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    /**
     * Retourne les quartiers sous forme de tableau
     */
    public function getQuartiersArray(): array
    {
        if (!$this->quartiers) {
            return [];
        }
        return array_map('trim', explode(',', $this->quartiers));
    }
}