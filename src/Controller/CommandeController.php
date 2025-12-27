<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Entity\Burger;
use App\Service\Interface\CommandeServiceInterface;
use App\Service\Interface\LivraisonServiceInterface;
use Doctrine\ORM\EntityManagerInterface; // AJOUTÉ
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commandes')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class CommandeController extends AbstractController
{
    private CommandeServiceInterface $commandeService;
    private LivraisonServiceInterface $livraisonService;
    private EntityManagerInterface $entityManager; // AJOUTÉ

    public function __construct(
        CommandeServiceInterface $commandeService,
        LivraisonServiceInterface $livraisonService,
        EntityManagerInterface $entityManager // AJOUTÉ
    ) {
        $this->commandeService = $commandeService;
        $this->livraisonService = $livraisonService;
        $this->entityManager = $entityManager; // AJOUTÉ
    }

    /**
     * Liste toutes les commandes avec pagination
     */
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $commandes = $this->commandeService->getAllCommandes(null, $page, 6);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
            'currentPage' => $page,
        ]);
    }

    /**
     * FILTRE les commandes
     */
    #[Route('/filtrer', name: 'app_commande_filtrer', methods: ['GET'])]
    public function filtrer(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $type = $request->query->get('type');
        $etat = $request->query->get('etat');
        $dateStr = $request->query->get('date');
        $clientId = $request->query->get('client_id');

        $date = $dateStr ? new \DateTime($dateStr) : null;

        $commandes = $this->commandeService->filtrerCommandes(
            $type,
            $etat,
            $date,
            $clientId ? (int)$clientId : null,
            $page
        );

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
            'currentPage' => $page,
            'filtres' => [
                'type' => $type,
                'etat' => $etat,
                'date' => $dateStr,
                'client_id' => $clientId
            ]
        ]);
    }

    /**
     * Affiche l'interface d'affectation des livraisons
     */
    #[Route('/livraisons', name: 'app_commande_livraison', methods: ['GET', 'POST'])]
    public function livraison(Request $request): Response
    {
        $commandesParZone = $this->commandeService->getCommandesGroupéesParZone();
        $livreurs = $this->livraisonService->getLivreursDisponibles();

        if ($request->isMethod('POST')) {
            $commandeIds = $request->request->all('commandes');
            $livreurId = $request->request->get('livreur');

            if (!empty($commandeIds) && $livreurId) {
                // Utilisation de la nouvelle méthode corrigée
                $livreur = $this->getLivreurById((int)$livreurId);

                if ($livreur && $this->livraisonService->affecterLivreur($commandeIds, $livreur)) {
                    $this->addFlash('success', count($commandeIds) . ' commande(s) affectée(s) avec succès.');
                } else {
                    $this->addFlash('error', 'Erreur lors de l\'affectation du livreur.');
                }
                return $this->redirectToRoute('app_commande_livraison');
            }
        }

        return $this->render('commande/livraison.html.twig', [
            'commandesParZone' => $commandesParZone,
            'livreurs' => $livreurs,
        ]);
    }

    /**
     * Affiche les détails d'une commande
     */
    #[Route('/{id}', name: 'app_commande_detail', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function detail(int $id): Response
    {
        $commandeData = $this->commandeService->getDetailCommande($id);

        if (!$commandeData) {
            $this->addFlash('error', 'Commande introuvable');
            return $this->redirectToRoute('app_commande_index');
        }

        return $this->render('commande/detail.html.twig', $commandeData);
    }

    /**
     * Marque une commande comme terminée
     */
    #[Route('/{id}/terminer', name: 'app_commande_terminer', methods: ['POST'])]
    public function terminer(int $id): Response
    {
        if ($this->commandeService->terminerCommande($id)) {
            $this->addFlash('success', 'Commande terminée avec succès');
        } else {
            $this->addFlash('error', 'Impossible de terminer cette commande');
        }

        return $this->redirectToRoute('app_commande_detail', ['id' => $id]);
    }

    /**
     * Annule une commande
     */
    #[Route('/{id}/annuler', name: 'app_commande_annuler', methods: ['POST'])]
    public function annuler(int $id): Response
    {
        if ($this->commandeService->annulerCommande($id)) {
            $this->addFlash('success', 'Commande annulée avec succès');
        } else {
            $this->addFlash('error', 'Erreur lors de l\'annulation de la commande');
        }

        return $this->redirectToRoute('app_commande_index');
    }

    /**
     * MÉTHODE CORRIGÉE : Utilise l'EntityManager injecté au lieu du container
     */
    private function getLivreurById(int $id): ?Livreur
    {
        return $this->entityManager->getRepository(Livreur::class)->find($id);
    }
   
}