<?php

namespace App\Controller;

use App\Entity\Message;  
use App\Form\MessageType;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement du message
            $entityManager->persist($message);
            $entityManager->flush();

            // Envoi d'un email
            $email = (new Email())
                ->from('fredemeyap@gmail.com')
                ->to('gerant@example.com')
                ->subject('Nouveau message de contact')
                ->html('<p><strong>Expéditeur :</strong> ' . htmlspecialchars($message->getEmail()) . '</p>' .
                       '<p><strong>Message :</strong></p>' .
                       '<p>' . nl2br(htmlspecialchars($message->getMessage())) . '</p>');

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}