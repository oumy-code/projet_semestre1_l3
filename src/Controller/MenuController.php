<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/gestion/menus')]
class MenuController extends AbstractController
{
    #[Route('/', name: 'app_menu_index', methods: ['GET'])]
    public function index(
        EntityManagerInterface $em, 
        PaginatorInterface $paginator, 
        Request $request
    ): Response {
        // On récupère TOUS les menus (QueryBuilder) sans filtrer l'archive 
        // pour que les menus grisés apparaissent dans la liste
        $queryBuilder = $em->getRepository(Menu::class)->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            2 // Votre limite actuelle par page
        );

        return $this->render('menu/index.html.twig', [
            'menus' => $pagination,
        ]);
    }

    #[Route('/nouveau', name: 'app_menu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($menu);
            $em->flush();

            $this->addFlash('success', 'Le menu a été créé avec succès !');
            return $this->redirectToRoute('app_menu_index');
        }

        return $this->render('menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_menu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Menu $menu, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Menu mis à jour !');
            return $this->redirectToRoute('app_menu_index');
        }

        return $this->render('menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette méthode utilise le nom de route attendu par votre Twig.
     * Elle inverse l'état de l'archive (Toggle).
     */
    #[Route('/{id}/supprimer', name: 'app_menu_delete', methods: ['POST'])]
    public function delete(Request $request, Menu $menu, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {
            // Inversion de l'état : si true devient false, si false devient true
            $menu->setArchive(!$menu->isArchive());
            $em->flush();

            $action = $menu->isArchive() ? 'archivé' : 'réactivé';
            $this->addFlash('success', "Le menu {$menu->getNom()} a été {$action}.");
        }

        return $this->redirectToRoute('app_menu_index');
    }
}