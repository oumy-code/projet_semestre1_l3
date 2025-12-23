<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return new Response("
            <html>
                <body style='font-family: sans-serif; text-align: center; padding-top: 50px;'>
                    <h1>✅ Brasil Burger Symfony est en ligne !</h1>
                    <p>Le déploiement sur Render a réussi.</p>
                    <p>Version PHP : " . PHP_VERSION . "</p>
                    <hr>
                    <p><small>Modifiez ce contrôleur pour afficher votre vraie page d'accueil.</small></p>
                </body>
            </html>
        ");
    }
}