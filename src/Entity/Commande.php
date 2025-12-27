<?php

namespace App\Entity;

use App\Enum\EtatCommande;
use App\Enum\TypeRecuperation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'commande')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false, name: 'id_client')]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class)]
    #[ORM\JoinColumn(name: 'id_gestionnaire')]
    private ?Gestionnaire $gestionnaire = null;

    #[ORM\ManyToOne(targetEntity: Zone::class)]
    #[ORM\JoinColumn(name: 'id_zone')]
    private ?Zone $zone = null;

    #[ORM\ManyToOne(targetEntity: Livreur::class)]
    #[ORM\JoinColumn(name: 'id_livreur')]
    private ?Livreur $livreur = null;

    #[ORM\Column(type: 'datetime', name: 'date_commande')]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, name: 'montant_total')]
    private ?string $montantTotal = null;

    #[ORM\Column(type: 'string', enumType: EtatCommande::class)]
    private ?EtatCommande $etat = null;

    #[ORM\Column(type: 'string', enumType: TypeRecuperation::class, name: 'type_recuperation')]
    private ?TypeRecuperation $typeRecuperation = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true, name: 'adresse_livraison')]
    private ?string $adresseLivraison = null;

    #[ORM\Column(type: 'datetime', name: 'date_modification')]
    private ?\DateTimeInterface $dateModification = null;

    #[ORM\OneToMany(targetEntity: LigneCommande::class, mappedBy: 'commande', cascade: ['persist'])]
    private Collection $lignesCommande;

    #[ORM\OneToOne(targetEntity: Paiement::class, mappedBy: 'commande')]
    private ?Paiement $paiement = null;

    public function __construct()
    {
        $this->lignesCommande = new ArrayCollection();
        $this->dateCommande = new \DateTime();
        $this->dateModification = new \DateTime();
        $this->etat = EtatCommande::EN_COURS;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): self
    {
        $this->gestionnaire = $gestionnaire;
        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;
        return $this;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): self
    {
        $this->livreur = $livreur;
        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(string $montantTotal): self
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    public function getEtat(): ?EtatCommande
    {
        return $this->etat;
    }

    public function setEtat(EtatCommande $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getTypeRecuperation(): ?TypeRecuperation
    {
        return $this->typeRecuperation;
    }

    public function setTypeRecuperation(TypeRecuperation $typeRecuperation): self
    {
        $this->typeRecuperation = $typeRecuperation;
        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(?string $adresseLivraison): self
    {
        $this->adresseLivraison = $adresseLivraison;
        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;
        return $this;
    }

    public function getLignesCommande(): Collection
    {
        return $this->lignesCommande;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->lignesCommande->contains($ligneCommande)) {
            $this->lignesCommande[] = $ligneCommande;
            $ligneCommande->setCommande($this);
        }
        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): self
    {
        $this->paiement = $paiement;
        return $this;
    }
}