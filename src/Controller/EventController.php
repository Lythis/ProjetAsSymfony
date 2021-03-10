<?php
// src/Controller/TestCSSController.php
namespace App\Controller;

use App\Entity\Event;
use App\Form\EventCreateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    /**
+   * @Route("/event/create")
+   */
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

            return $this->redirectToRoute('event\create.html.twig');
        }

        return $this->render('event\create.html.twig', [
            'eventForm' => $form->createView(),
            'csrf_protection' => false,
        ]);
    }

    /**
+   * @Route("/event/delete")
+   */
    public function removeEvent(): Response
    {
        return $this->render('event\delete.html.twig', [
            
        ]);
    }

    /**
+   * @Route("/event/edit")
+   */
    public function editEvent(): Response
    {
        return $this->render('event\edit.html.twig', [
            
        ]);
    }

}