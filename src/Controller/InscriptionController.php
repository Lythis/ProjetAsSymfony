<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Category;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Security\LoginAuthenticator;
use App\Form\InscriptionType;
use DateTime;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class InscriptionController extends AbstractController
{

    public function __construct(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }

    
     /**
     * @Route("/inscription", name="app_inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer):  Response
    {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);
        $admins = $this->getDoctrine()->getRepository(User::class)->findBy(
            array('role' => 'admin'));
        $form->handleRequest($request);

        $emails = array();
        foreach($admins as $admin)
        {
            $emails[] = $admin->getEmail();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setIsEnabled(false);
            $message = (new \Swift_Message())
                ->setFrom('Groupe6Association@gmail.com')
                ->setTo($emails)
                ->setSubject('Demande inscription')
                ->setBody(
                    $this->renderView('email/inscription.html.twig', 
                ),
            'text/html'
        );        

        $this->swiftMailer->send($message);


            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user
                ->setPassword($password)
                ->setRole("student")
                ->setPasswordRequestedAt(new DateTime());            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
        };
        return $this->render('security/inscription.html.twig', [
            'inscriptionForm' => $form->createView(),
        ]);
    }

     /**
     * @Route("/validation", name="not_connected")
     */
    public function notConnected(): Response
    {
        return $this->render('security/notConnected.html.twig');
    }
}