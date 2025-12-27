<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Form\BurgerType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion/burgers')]
class BurgerController extends AbstractController
{
    #[Route('/', name: 'app_burger_index', methods: ['GET'])]
    public function index(BurgerRepository $burgerRepository, Request $request): Response
    {
        $limit = 5; // Augmenté un peu pour la visibilité
        $page = (int)$request->query->get('page', 1);
        $search = $request->query->get('search');

        // IMPORTANT : Vérifiez que findPaginated dans votre Repository 
        // ne filtre plus par archive = false pour tout voir en admin
        $paginator = $burgerRepository->findPaginated($page, $limit, $search);
        $pagesCount = ceil(count($paginator) / $limit);

        return $this->render('burger/index.html.twig', [
            'burgers' => $paginator,
            'currentPage' => $page,
            'pagesCount' => $pagesCount,
            'searchTerm' => $search
        ]);
    }

    #[Route('/nouveau', name: 'app_burger_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($burger);
            $em->flush();
            $this->addFlash('success', 'Nouveau burger ajouté !');
            return $this->redirectToRoute('app_burger_index');
        }

        return $this->render('burger/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/modifier', name: 'app_burger_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Burger $burger, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Burger mis à jour !');
            return $this->redirectToRoute('app_burger_index');
        }

        return $this->render('burger/edit.html.twig', [
            'burger' => $burger,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'app_burger_delete', methods: ['POST'])]
    public function delete(Request $request, Burger $burger, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$burger->getId(), $request->request->get('_token'))) {
            // INVERSION : On bascule entre archivé et actif
            $burger->setArchive(!$burger->isArchive());
            $em->flush();

            $msg = $burger->isArchive() ? 'Burger archivé !' : 'Burger réactivé !';
            $this->addFlash('success', $msg);
        }

        return $this->redirectToRoute('app_burger_index');
    }
}