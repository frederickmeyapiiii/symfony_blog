<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire; // Assurez-vous de l'importer
use App\Form\CommentaireType; // Assurez-vous de l'importer
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/api/articles', name: 'get_articles', methods: ['GET'])]
    public function getArticles(EntityManagerInterface $entityManager): JsonResponse
    {
        $articles = $entityManager->getRepository(Article::class)->findBy([], ['dateTime' => 'DESC'], 5);
        
        return $this->json($articles, 200, [], ['groups' => 'article:read']);
    }

    #[Route('/api/articles/{id}', name: 'get_article', methods: ['GET'])]
    public function getArticle(?Article $article): JsonResponse
    {
        if (!$article) {
            return $this->json(['message' => 'Article non trouvé'], 404);
        }

        return $this->json($article, 200, [], ['groups' => 'article:read']);
    }

    #[Route('/article/{id}', name: 'article_show', methods: ['GET', 'POST'])]
    public function show(EntityManagerInterface $entityManager, Article $article, Request $request): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setArticle($article);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/show_article.html.twig', [
            'article' => $article,
            'commentaires' => $article->getCommentaires(),
            'form' => $form->createView(), // Passer le formulaire au template
        ]);
    }
    #[Route('/articles', name: 'blog_index', methods: ['GET'])]
public function index(EntityManagerInterface $entityManager): Response
{
    $articles = $entityManager->getRepository(Article::class)->findAll();
    return $this->render('blog/index.html.twig', ['articles' => $articles]);
}
}