<?php

namespace App\Controller;

use App\Service\Interface\StatistiqueServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur du Dashboard
 * Principe SOLID: Dependency Inversion - dépend des interfaces, pas des implémentations
 */
#[Route('/gestionnaire')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class DashboardController extends AbstractController
{
    private StatistiqueServiceInterface $statistiqueService;

    public function __construct(StatistiqueServiceInterface $statistiqueService)
    {
        $this->statistiqueService = $statistiqueService;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $statistiques = $this->statistiqueService->getStatistiquesDuJour();

        return $this->render('dashboard/index.html.twig', [
            'gestionnaire' => $this->getUser(),
            'stats' => $statistiques
        ]);
    }
}