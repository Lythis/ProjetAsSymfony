<?php
// src/Controller/TestCSSController.php
namespace App\Controller;

use App\Entity\Event;
use App\Form\EventCreateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
    * @Route("/create", name="event_create_page")
    */
    public function createEvent(Request $request): Response
    {
        $event = new Event();

        $form = $this->createForm(EventCreateType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();

            return $this->redirectToRoute("event_create_page");
        }

        return $this->render('event/create.html.twig', [
            'eventForm' => $form->createView(),
        ]);
    }

    /**
    * @Route("/delete", name="event_delete_page")
    */
    public function removeEvent(): Response
    {
        return $this->render('event/delete.html.twig', [
            
        ]);
    }

    /**
    * @Route("/edit", name="event_edit_page")
    */
    public function editEvent(): Response
    {
        return $this->render('event/edit.html.twig', [
            
        ]);
    }

}