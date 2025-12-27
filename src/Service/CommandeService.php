<?php

namespace App\Service;

use App\Service\Interface\CommandeServiceInterface;
use App\Repository\Interface\CommandeRepositoryInterface;
use App\DTO\CommandeFilterDTO;
use App\Entity\Commande;
use App\Enum\EtatCommande;
use App\Enum\TypeRecuperation;
use App\Entity\Livreur; // Important pour les types
use Doctrine\ORM\EntityManagerInterface;

class CommandeService implements CommandeServiceInterface
{
    private CommandeRepositoryInterface $commandeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CommandeRepositoryInterface $commandeRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->commandeRepository = $commandeRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllCommandes(?CommandeFilterDTO $filters = null, int $page = 1, int $limit = 6): array 
    {
        return $this->commandeRepository->findWithFilters($filters, $page, $limit);
    }

    public function filtrerCommandes(?string $type, ?string $etat, ?\DateTime $date, ?int $clientId, int $page = 1): array
    {
        $filters = new CommandeFilterDTO();
        
        if ($etat) {
            $etatValue = strtolower($etat);
            if ($etatValue === 'valide') { $etatValue = 'termine'; }

            $enum = EtatCommande::tryFrom($etatValue);
            if ($enum) { $filters->setEtat($enum); }
        }

        if ($type) {
            $typeEnum = TypeRecuperation::tryFrom(strtolower($type));
            if ($typeEnum) { $filters->setTypeRecuperation($typeEnum); }
        }

        if ($date) {
            $filters->setDateDebut($date);
            $dateFin = clone $date;
            $dateFin->setTime(23, 59, 59);
            $filters->setDateFin($dateFin);
        }

        return $this->getAllCommandes($filters, $page, 6); 
    }

    public function getCommandeById(int $id): ?Commande
    {
        return $this->commandeRepository->find($id);
    }

    /**
     * SOLUTION À L'ERREUR : Ajout de la méthode manquante demandée par l'interface
     */
    public function getDetailCommande(int $id): ?array
    {
        $commande = $this->getCommandeById($id);
        if (!$commande) { return null; }

        return [
            'commande' => $commande,
            'lignes'   => $commande->getLignesCommande(),
            'paiement' => $commande->getPaiement(),
            'client'   => $commande->getClient(),
            'zone'     => $commande->getZone(),
            'livreur'  => $commande->getLivreur()
        ];
    }

    public function annulerCommande(int $id): bool
    {
        $commande = $this->getCommandeById($id);
        if (!$commande || $commande->getEtat() === EtatCommande::TERMINE) { return false; }
        
        $commande->setEtat(EtatCommande::ANNULE);
        $commande->setDateModification(new \DateTime());
        $this->entityManager->flush();
        return true;
    }

    public function terminerCommande(int $id): bool
    {
        $commande = $this->getCommandeById($id);
        if (!$commande || $commande->getEtat() === EtatCommande::ANNULE) { return false; }
        
        $commande->setEtat(EtatCommande::TERMINE);
        $commande->setDateModification(new \DateTime());
        $this->entityManager->flush();
        return true;
    }

    public function getCommandesGroupéesParZone(): array
    {
        // On appelle la méthode de votre Repository qui fait déjà le groupement
        return $this->commandeRepository->findCommandesALivrerParZone();
    }

    /**
     * Ajout pour le contrôleur : accès aux livreurs via l'EntityManager
     */
    public function getLivreursDisponibles(): array
    {
        return $this->entityManager->getRepository(Livreur::class)->findBy(['disponible' => true]);
    }
}