<?php

namespace App\Controller;

use App\Repository\ArticleRepository; // Assurez-vous d'importer le bon namespace
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        // Récupérer les 5 derniers articles
        $articles = $articleRepository->findBy([], ['id' => 'DESC'], 5);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles,
        ]);
    }
}