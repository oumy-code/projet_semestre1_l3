<?php

namespace App\Service;

use App\Service\Interface\LivraisonServiceInterface;
use App\Repository\Interface\CommandeRepositoryInterface;
use App\Entity\Livreur;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\EtatCommande;

class LivraisonService implements LivraisonServiceInterface
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

    public function affecterLivreur(array $commandeIds, Livreur $livreur): bool
    {
        try {
            foreach ($commandeIds as $commandeId) {
                $commande = $this->commandeRepository->find($commandeId);
                if ($commande) {
                    $commande->setLivreur($livreur);
                    
                    // IMPORTANT : Changer l'état pour que la commande sorte de la liste d'attente
                    // Adapte 'EN_LIVRAISON' selon ton Enum ou tes constantes
                    if (method_exists($commande, 'setEtat')) {
                    $commande->setEtat(EtatCommande::TERMINE);
                    }

                    $commande->setDateModification(new \DateTime());
                }
            }

            // Mettre le livreur comme non disponible
            // Vérifie bien que cette méthode existe dans ton entité Livreur
            if (method_exists($livreur, 'setDisponible')) {
                $livreur->setDisponible(false);
            }
            
            $this->entityManager->flush();
            
            return true;
        } catch (\Exception $e) {
            // Optionnel : logger l'erreur pour le debug
            return false;
        }
    }

    public function libererLivreur(int $livreurId): bool
    {
        try {
            $livreur = $this->entityManager->getRepository(Livreur::class)->find($livreurId);
            
            if (!$livreur) {
                return false;
            }

            $livreur->setDisponible(true);
            $this->entityManager->flush();
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getLivreursDisponibles(): array
    {
        return $this->entityManager
            ->getRepository(Livreur::class)
            ->findBy(['disponible' => true]);
    }
}