<?php

namespace App\Controller;

use App\Service\Interface\StatistiqueServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur des statistiques (API JSON)
 */
#[Route('/gestionnaire/api/statistiques')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class StatistiqueController extends AbstractController
{
    private StatistiqueServiceInterface $statistiqueService;

    public function __construct(StatistiqueServiceInterface $statistiqueService)
    {
        $this->statistiqueService = $statistiqueService;
    }

    #[Route('/jour', name: 'app_statistiques_jour', methods: ['GET'])]
    public function statistiquesDuJour(): JsonResponse
    {
        $stats = $this->statistiqueService->getStatistiquesDuJour();

        return $this->json([
            'commandesEnCours' => $stats->getCommandesEnCours(),
            'commandesValidees' => $stats->getCommandesValidees(),
            'recettesJournalieres' => $stats->getRecettesJournalieres(),
            'commandesAnnulees' => $stats->getCommandesAnnulees(),
            'topVentes' => $stats->getTopVentes(),
            'dernieresCommandes' => array_map(function($commande) {
                return [
                    'id' => $commande->getId(),
                    'client' => [
                        'nom' => $commande->getClient()->getNom(),
                        'prenom' => $commande->getClient()->getPrenom(),
                    ],
                    'montant' => $commande->getMontantTotal(),
                    'etat' => $commande->getEtat()->value,
                ];
            }, $stats->getDernieresCommandes())
        ]);
    }

    #[Route('/commandes-en-cours', name: 'app_statistiques_commandes_en_cours', methods: ['GET'])]
    public function commandesEnCours(): JsonResponse
    {
        return $this->json([
            'count' => $this->statistiqueService->getCommandesEnCours()
        ]);
    }

    #[Route('/recettes', name: 'app_statistiques_recettes', methods: ['GET'])]
    public function recettes(): JsonResponse
    {
        return $this->json([
            'recettes' => $this->statistiqueService->getRecettesJournalieres()
        ]);
    }

    #[Route('/top-ventes', name: 'app_statistiques_top_ventes', methods: ['GET'])]
    public function topVentes(): JsonResponse
    {
        return $this->json([
            'topVentes' => $this->statistiqueService->getTopVentesDuJour(5)
        ]);
    }
}