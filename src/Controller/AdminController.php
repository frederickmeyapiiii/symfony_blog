<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Message;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_article_list');
    }

    #[Route('/admin/article/create', name: 'admin_article_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();
            $this->handleImageUpload($imageFile, $article);

            $article->setDateTime(new \DateTime());
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article créé avec succès !');
            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Méthode pour gérer l'upload d'images
    private function handleImageUpload(?UploadedFile $imageFile, Article $article): void
    {
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            try {
                $imageFile->move($this->getParameter('images_directory'), $newFilename);
                $article->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
            }
        }
    }

    #[Route('/admin/article/{id}/edit', name: 'admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();
            $this->handleImageUpload($imageFile, $article);

            $em->flush();
            $this->addFlash('success', 'Article modifié avec succès !');
            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/admin/article/{id}/delete', name: 'admin_article_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $em->remove($article);
        $em->flush();

        $this->addFlash('success', 'Article supprimé avec succès !');
        return $this->redirectToRoute('admin_article_list');
    }

    #[Route('/admin/articles', name: 'admin_article_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('admin/article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/admin/messages', name: 'admin_message_list')]
    public function listMessages(EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(Message::class)->findAll();
        return $this->render('admin/message/list.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/admin/message/{id}/delete', name: 'admin_message_delete', methods: ['POST'])]
    public function deleteMessage(int $id, EntityManagerInterface $em): Response
    {
        $message = $em->getRepository(Message::class)->find($id);
        if (!$message) {
            throw $this->createNotFoundException('Message non trouvé');
        }

        $em->remove($message);
        $em->flush();

        $this->addFlash('success', 'Message supprimé avec succès !');
        return $this->redirectToRoute('admin_message_list');
    }
}