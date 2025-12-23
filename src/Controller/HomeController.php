<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    
#[Route('/', name: 'app_home')] 
public function index(\Doctrine\DBAL\Connection $connection): Response
{
   
    $dbName = $connection->fetchOne('SELECT current_database()');

    return new Response("Bravo ! Le site est en ligne et connecté à la base : " . $dbName);
}
}
