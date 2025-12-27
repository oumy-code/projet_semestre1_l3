<?php

namespace App\Entity;

use App\Enum\ModePaiement;
use App\Enum\StatutPaiement;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Paiement
 */
#[ORM\Entity]
#[ORM\Table(name: 'paiement')]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Commande::class, inversedBy: 'paiement')]
    #[ORM\JoinColumn(nullable: false, unique: true, name: 'id_commande')]
    private ?Commande $commande = null;

    #[ORM\Column(type: 'datetime', name: 'date_paiement')]
    private ?\DateTimeInterface $datePaiement = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(type: 'string', enumType: ModePaiement::class, name: 'mode_paiement')]
    private ?ModePaiement $modePaiement = null;

    #[ORM\Column(type: 'string', enumType: StatutPaiement::class)]
    private ?StatutPaiement $statut = null;

    public function __construct()
    {
        $this->datePaiement = new \DateTime();
        $this->statut = StatutPaiement::EN_ATTENTE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(\DateTimeInterface $datePaiement): self
    {
        $this->datePaiement = $datePaiement;
        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getModePaiement(): ?ModePaiement
    {
        return $this->modePaiement;
    }

    public function setModePaiement(ModePaiement $modePaiement): self
    {
        $this->modePaiement = $modePaiement;
        return $this;
    }

    public function getStatut(): ?StatutPaiement
    {
        return $this->statut;
    }

    public function setStatut(StatutPaiement $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}