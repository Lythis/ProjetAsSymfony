<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventCreateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
    * @Route("/adminshow", name="event_admin")
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
    * @Route("/eventshow", name="event_show")
    */
    public function eventShow(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render('event/show.html.twig', [
            'events' => $events
        ]);
    }

    /**
    * @Route("/adminshow/delete/{id}", name="event_delete")
    */
    public function adminEventDelete(Event $event): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute("event_admin");
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

            if (!empty($form['image']->getData()))
            {
                $file = $form['image']->getData();
                $file->move('img', $file->getClientOriginalName());
                $event->setImage($file->getClientOriginalName());
            }
            else
            {
                $event->setImage('default.jpg');
            }

            if (!empty($form['thumbnail']->getData()))
            {
                $file = $form['thumbnail']->getData();
                $file->move('thumbnail', $file->getClientOriginalName());
                $event->setThumbnail($file->getClientOriginalName());
            }
            else
            {
                $event->setThumbnail('default.jpg');
            }

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
    * @Route("/adminshow/edit/{id}", name="event_edit")
    */
    public function editEvent(Event $event, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EventCreateType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form['image']->getData()))
            {
                $file = $form['image']->getData();
                $file->move('img', $file->getClientOriginalName());
                $event->setImage($file->getClientOriginalName());
            }
            else
            {
                $event->setImage('default.jpg');
            }

            if (!empty($form['thumbnail']->getData()))
            {
                $file = $form['thumbnail']->getData();
                $file->move('thumbnail', $file->getClientOriginalName());
                $event->setThumbnail($file->getClientOriginalName());
            }
            else
            {
                $event->setThumbnail('default.jpg');
            }
            
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