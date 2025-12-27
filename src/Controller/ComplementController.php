<?php

namespace App\Controller;

use App\Entity\Complement;
use App\Form\ComplementFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion/complements')]
class ComplementController extends AbstractController
{
    #[Route('/', name: 'app_complement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        // On récupère tout (actifs + archivés) pour gérer l'affichage grisé
        $complements = $em->getRepository(Complement::class)->findAll();

        return $this->render('complement/index.html.twig', [
            'complements' => $complements,
        ]);
    }

    #[Route('/nouveau', name: 'app_complement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $complement = new Complement();
        $form = $this->createForm(ComplementFormType::class, $complement);      
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($complement);
            $em->flush();

            $this->addFlash('success', 'Complément ajouté avec succès !');
            return $this->redirectToRoute('app_complement_index');
        }

        return $this->render('complement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_complement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Complement $complement, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ComplementFormType::class, $complement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Complément mis à jour !');
            return $this->redirectToRoute('app_complement_index');
        }

        return $this->render('complement/edit.html.twig', [
            'complement' => $complement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette méthode permet d'activer ou de désactiver un produit sans le supprimer
     */
    #[Route('/{id}/toggle-archive', name: 'app_complement_toggle_archive', methods: ['POST'])]
    public function toggleArchive(Complement $complement, EntityManagerInterface $em): Response
    {
        $complement->setArchive(!$complement->isArchive());
        $em->flush();

        $statut = $complement->isArchive() ? 'désactivé' : 'réactivé';
        $this->addFlash('success', "Le complément {$complement->getNom()} a été {$statut}.");

        return $this->redirectToRoute('app_complement_index');
    }
}
