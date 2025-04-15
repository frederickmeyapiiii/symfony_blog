<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class MentionController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('index.html.twig');  // Le template pour la page d'accueil
    }
    

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('contact/index.html.twig');  // Le template pour la page de contact
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blog(): Response
    {
        return $this->render('blog/index.html.twig');  // Le template pour la page de blog
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(): Response
    {
        return $this->render('admin/index.html.twig');  // Le template pour la page d'administration
    }

        /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function mentionsLegales(): Response
    {
        return $this->render('mentions/mentions_legales.html.twig');
    }

    /**
     * @Route("/politique-confidentialite", name="politique_confidentialite")
     */
    public function politiqueConfidentialite(): Response
    {
        return $this->render('mentions/politique_confidentialite.html.twig');
    }
     /**
     * @Route("/admin/messages", name="admin_messages")
     */
    public function index(): Response
    {
        return $this->render('admin/messages.html.twig' );
    }
}