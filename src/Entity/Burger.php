<?php

namespace App\Entity;

use App\Repository\BurgerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ORM\Table(name: 'burger')]
class Burger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $image = null; // Propriété existante

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $archive = false;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    public function __construct() {
        $this->dateCreation = new \DateTime();
    }

    // --- GETTERS & SETTERS INDISPENSABLES POUR TWIG ---

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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    // LA MÉTHODE MANQUANTE :
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function isArchive(): bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;
        return $this;
    }
}