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
    public function adminEvent(): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

            return $this->render('event/admin.html.twig', [
                'events' => $events
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/eventshow", name="event_show")
    */
    public function eventShow(): Response
    {
        if ($this->getUser())
        {
            $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

            return $this->render('event/show.html.twig', [
                'events' => $events
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/adminshow/delete/{id}", name="event_delete")
    */
    public function adminEventDelete(Event $event): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();

            return $this->redirectToRoute("event_admin");
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/create", name="event_create_page")
    */
    public function createEvent(Request $request): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

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

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/adminshow/edit/{id}", name="event_edit")
    */
    public function editEvent(Event $event, Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }
            
            $image = $event->getImage();
            $thumbnail = $event->getThumbnail();

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
                    $event->setImage($image);
                }

                if (!empty($form['thumbnail']->getData()))
                {
                    $file = $form['thumbnail']->getData();
                    $file->move('thumbnail', $file->getClientOriginalName());
                    $event->setThumbnail($file->getClientOriginalName());
                }
                else
                {
                    $event->setThumbnail($thumbnail);
                }
                
                $em->persist($event);
                $em->flush();
                return $this->redirectToRoute('event_admin', [
                    
                ]);
            }
            return $this->render('event/edit.html.twig', [
                'eventForm' => $form->createView()
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/delete", name="event_delete_page")
    */
    public function removeEvent(): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            return $this->render('event/delete.html.twig', [
                
            ]);
        }

        return $this->redirectToRoute("app_login");
    }
}