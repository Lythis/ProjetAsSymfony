<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventCreateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
    * @Route("/adminShow", name="event_admin")
    */
    public function adminEvent(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render('event/admin.html.twig', [
            'events' => $events
        ]);
    }

    /**
    * @Route("/events", name="event_show")
    */
    public function eventShow(Request $request): Response
    {

        return $this->render('event/show.html.twig', [
        ]);
    }

    /**
    * @Route("/adminShow/delet/{id}", name="event-delete")
    */
    public function adminEventDelete(Event $event): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        return new Response("Evenement supprimé");
    }

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
    * @Route("/adminShow/edit/{id}", name="event-edit")
    */
    public function editEvent(Event $event, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EventCreateType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Article Updated! Inaccuracies squashed!');
            return $this->redirectToRoute('event_admin', [
                'id' => $event->getId(),
            ]);
        }
        return $this->render('event/edit.html.twig', [
            'eventForm' => $form->createView()
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
}