<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
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
    public function userAsk(Request $request): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            if ($request->query->get('message') !== null)
            {
                $this->addFlash('notice', $request->query->get('message'));
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

            $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy(
                array('user' => $user->getId()),
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($student);
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute("user_ask", [
                'message' => 'Demande pour l\'utilisateur '.$user->getEmail().' rejetée.',
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

     /**
    * @Route("/request/accept/{id}", name="user_accept")
    */
    public function adminUserAccept(User $user): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $user->setIsEnabled(1);
            $entityManager->flush();

            $message = (new \Swift_Message())
                ->setFrom('Groupe6Association@gmail.com')
                ->setTo($user->getEmail())
                ->setSubject('Demande inscription')
                ->setBody(
                    $this->renderView('email/accept.html.twig'),
                );

            $this->swiftMailer->send($message);

            return $this->redirectToRoute("user_ask", [
                'message' => 'Demande pour l\'utilisateur '.$user->getEmail().' acceptée.',
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    
    /**
    * @Route("/student", name="user_list")
    */
    public function studentShow(Request $request): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getIsEnabled() == 0)
            {
                return $this->redirectToRoute("not_connected");
            }

            if ($request->query->get('message') !== null)
            {
                $this->addFlash('notice', $request->query->get('message'));
            }
            
            $students = $this->getDoctrine()->getRepository(Student::class)->findAll();

            return $this->render('admin/eleve.html.twig', [
                'students' => $students,
            ]);
        }
    }

   
    /**
    * @Route("/student/edit/{id}", name="user_edit")
    */
    public function studentEdit(Student $student, Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $form = $this->createForm(StudentType::class, $student);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($student);
                $em->flush();

                return $this->redirectToRoute("user_list", [
                    'message' => 'Elève '.$student->getFirstName().' modifié avec succès.',
                ]);
            }

            return $this->render('user/modify.html.twig', [
                'studentForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute("app_login");
    }
}