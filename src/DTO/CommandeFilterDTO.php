<?php

namespace App\DTO;

use App\Enum\EtatCommande;
use App\Enum\TypeRecuperation;

/**
 * DTO pour les filtres de commande
 */
class CommandeFilterDTO
{
    private ?EtatCommande $etat = null;
    private ?TypeRecuperation $typeRecuperation = null;
    private ?\DateTimeInterface $dateDebut = null;
    private ?\DateTimeInterface $dateFin = null;
    private ?string $clientNom = null;
    private ?int $burgerId = null;
    private ?int $menuId = null;

    public function getEtat(): ?EtatCommande
    {
        return $this->etat;
    }

    public function setEtat(?EtatCommande $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getTypeRecuperation(): ?TypeRecuperation
    {
        return $this->typeRecuperation;
    }

    public function setTypeRecuperation(?TypeRecuperation $typeRecuperation): self
    {
        $this->typeRecuperation = $typeRecuperation;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getClientNom(): ?string
    {
        return $this->clientNom;
    }

    public function setClientNom(?string $clientNom): self
    {
        $this->clientNom = $clientNom;
        return $this;
    }

    public function getBurgerId(): ?int
    {
        return $this->burgerId;
    }

    public function setBurgerId(?int $burgerId): self
    {
        $this->burgerId = $burgerId;
        return $this;
    }

    public function getMenuId(): ?int
    {
        return $this->menuId;
    }

    public function setMenuId(?int $menuId): self
    {
        $this->menuId = $menuId;
        return $this;
    }
}