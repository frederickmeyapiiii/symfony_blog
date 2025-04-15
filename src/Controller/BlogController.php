<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les articles depuis la base de données et retourner à la vue
        $articles = $entityManager->getRepository(Article::class)->findBy([], ['dateTime' => 'DESC']); // Tri par date desc

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer un formulaire pour un nouveau commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);

        // Traiter la soumission du formulaire de commentaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le commentaire à l'article
            $commentaire->setArticle($article);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté.');

            // Rediriger pour éviter la double soumission du formulaire
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/show_article.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'commentaires' => $article->getCommentaires(),
        ]);
    }
}