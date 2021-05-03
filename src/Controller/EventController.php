<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Student;
use App\Entity\SubscriptionEvent;
use App\Form\EventCreateType;
use DateTime;
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
    public function eventShow(Request $request): Response
    {
        if ($this->getUser()->getIsEnabled() == 0)
        {
            return $this->redirectToRoute("not_connected");
        }

        if ($this->getUser())
        {
            $events = $this->getDoctrine()->getRepository(Event::class)->findBy(
                array(),
                array('date' => 'DESC'),
            );

            $user = $this->getUser();
            $student = null;
            $subscribedEvents = [];
            if ($user->getRole() == 'student')
            {
                $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy(
                    array('user' => $user->getId()),
                );

                $eventSubscriptions = $this->getDoctrine()->getRepository(SubscriptionEvent::class)->findBy(
                    array('student' => $student),
                );

                foreach ($eventSubscriptions as $eventSub)
                {
                    array_push($subscribedEvents, $eventSub->getEvent());
                }
            }

            if ($request->query->get('message') !== null)
            {
                $this->addFlash('notice', $request->query->get('message'));
            }

            return $this->render('event/show.html.twig', [
                'events' => $events,
                'student' => $student,
                'subscribedEvents' => $subscribedEvents,
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/eventshow/{id}", name="event_details")
    */
    public function eventDetails(Event $event, Request $request): Response
    {
        if ($this->getUser()->getIsEnabled() == 0)
        {
            return $this->redirectToRoute("not_connected");
        }

        if ($this->getUser())
        {
            $user = $this->getUser();
            $student = null;
            $isSubscribed = false;
            if ($user->getRole() == 'student')
            {
                $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy(
                    array('user' => $user->getId()),
                );

                $eventSubscription = $this->getDoctrine()->getRepository(SubscriptionEvent::class)->findBy(
                    array(
                        'student' => $student,
                        'event' => $event,
                    ),
                );

                if (!empty($eventSubscription))
                {
                    $isSubscribed = true;
                }
            }

            if ($request->query->get('message') !== null)
            {
                $this->addFlash('notice', $request->query->get('message'));
            }

            return $this->render('event/details.html.twig', [
                'event' => $event,
                'student' => $student,
                'isSubscribed' => $isSubscribed,
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

            return $this->redirectToRoute("event_admin", [
                'message' => 'Evénement '.$event->getName().' supprimé avec succès.',
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/eventshow/subscribe/{id}", name="event_subscribe")
    */
    public function eventSubscribe(Event $event): Response
    {

        if ($this->getUser()->getIsEnabled() == 0)
        {
            return $this->redirectToRoute("not_connected");
        }

        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'student')
            {
                return $this->redirectToRoute("event_show");
            }

            $user = $this->getUser();
            $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy(
                array('user' => $user->getId()),
            );

            $eventSub = new SubscriptionEvent();
            $eventSub->setDate(new DateTime());
            $eventSub->setStudent($student);
            $eventSub->setEvent($event);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventSub);
            $entityManager->flush();

            return $this->redirectToRoute("event_show", [
                'message' => 'Vous êtes désormais inscrit à l\'événement '.$event->getName().'.',
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/eventshow/unsubscribe/{id}", name="event_unsubscribe")
    */
    public function eventUnsubscribe(Event $event): Response
    {
        if ($this->getUser()->getIsEnabled() == 0)
        {
            return $this->redirectToRoute("not_connected");
        }

        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'student')
            {
                return $this->redirectToRoute("event_show");
            }

            $user = $this->getUser();
            $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy(
                array('user' => $user->getId()),
            );

            $eventSub = $this->getDoctrine()->getRepository(SubscriptionEvent::class)->findOneBy(
                array(
                    'student' => $student->getId(),
                    'event' => $event->getId(),
                ),
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eventSub);
            $entityManager->flush();

            return $this->redirectToRoute("event_show", [
                'message' => 'Vous n\'êtes désormais plus inscrit à l\'événement '.$event->getName().'.',
            ]);
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

                return $this->redirectToRoute("event_admin", [
                    'message' => 'Evénement '.$event->getName().' créé avec succès.',
                ]);
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
                    'message' => 'Evénement '.$event->getName().' modifié avec succès.',
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