<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EventCreateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("")
 */
class AdminController extends AbstractController
{

    public function __construct(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }

    /**
    * @Route("/request/pending", name="user_ask")
    */
    public function adminEvent(): Response
    {

        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $users = $this->getDoctrine()->getRepository(User::class)->findBy(
                array(  
                    'isEnabled' => 0,
                    'role' => 'student'
                ));
                
            return $this->render('admin/request.html.twig', [
                'users' => $users
            ]);
        }

         return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/request/deny/{id}", name="user_deny")
    */
    public function adminUserDeny(User $user): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute("user_ask");
        }

        return $this->redirectToRoute("app_login");
    }

     /**
    * @Route("/request/accept/{id}", name="user_accept")
    */
    public function adminUserAccept(User $user,MailerInterface $mailer): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $user->setStatus(1);
            $entityManager->flush();

            $message = (new \Swift_Message())
                ->setFrom('Groupe6Association@gmail.com')
                ->setTo($user->getEmail())
                ->setSubject('Demande inscription')
                ->setBody(
                    $this->renderView('email/accept.html.twig'),
                );

            $this->swiftMailer->send($message);

            return $this->redirectToRoute("user_ask");
        }

        return $this->redirectToRoute("app_login");
    }
}