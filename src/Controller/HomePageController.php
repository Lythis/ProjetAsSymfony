<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(): Response
    {
        if ($this->getUser())
        {
            $events = $this->getDoctrine()->getRepository(Event::class)->findBy(
                array(),
                array('date' => 'DESC'),
                5,
            );

            return $this->render('home_page/index.html.twig', [
                'events' => $events,
            ]);
        }

        return $this->redirectToRoute("app_login");
    }
}