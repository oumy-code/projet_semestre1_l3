<?php

namespace App\Service\Interface;

use App\DTO\CommandeFilterDTO;
use App\Entity\Commande;

interface CommandeServiceInterface
{
    // Ajout des paramètres de pagination
    public function getAllCommandes(?CommandeFilterDTO $filters = null, int $page = 1, int $limit = 6): array;
    
    public function getCommandeById(int $id): ?Commande;
    public function annulerCommande(int $id): bool;
    public function terminerCommande(int $id): bool;
    public function getCommandesGroupéesParZone(): array;
    public function getDetailCommande(int $id): ?array;
    
    // Corrigé pour inclure la pagination lors du filtrage
    public function filtrerCommandes(?string $type, ?string $etat, ?\DateTime $date, ?int $clientId, int $page = 1): array;
}