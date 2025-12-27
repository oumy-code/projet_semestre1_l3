<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Menu
 */
#[ORM\Entity]
#[ORM\Table(name: 'menu')]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $archive = false;

    #[ORM\Column(type: 'datetime', name: 'date_creation')]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\OneToMany(targetEntity: CompositionMenu::class, mappedBy: 'menu', cascade: ['persist', 'remove'])]
    private Collection $compositions;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->compositions = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getCompositions(): Collection
    {
        return $this->compositions;
    }

    public function addComposition(CompositionMenu $composition): self
    {
        if (!$this->compositions->contains($composition)) {
            $this->compositions[] = $composition;
            $composition->setMenu($this);
        }
        return $this;
    }
    // src/Entity/Menu.php

public function removeComposition(CompositionMenu $composition): self
{
    if ($this->compositions->removeElement($composition)) {
        // On force le côté "Menu" de la composition à null pour rompre la relation
        if ($composition->getMenu() === $this) {
            $composition->setMenu(null);
        }
    }

    return $this;
}

    /**
     * Calcule le prix total du menu
     */
    public function getPrixTotal(): float
    {
        $total = 0;
        foreach ($this->compositions as $composition) {
            if ($composition->getBurger()) {
                $total += (float) $composition->getBurger()->getPrix() * $composition->getQuantite();
            }
            if ($composition->getComplement()) {
                $total += (float) $composition->getComplement()->getPrix() * $composition->getQuantite();
            }
        }
        return $total;
    }
    public function setPrixTotal(float $prixTotal): self
{
    $this->prixTotal = $prixTotal;
    return $this;
}
    
}