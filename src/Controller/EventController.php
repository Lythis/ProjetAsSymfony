<?php
// src/Controller/TestCSSController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/event")
*/
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_page")
     */
    public function indexEvent(): Response
    {
        return $this->render('event/index.html.twig', [

        ]);
    }

    /**
    * @Route("/create", name="create_event_page")
    */
    public function createEvent(): Response
    {
        return $this->render('event/create.html.twig', [
            
        ]);
    }

    /**
    * @Route("/delete", name="delete_event_page")
    */
    public function removeEvent(): Response
    {
        return $this->render('event/delete.html.twig', [
            
        ]);
    }

    /**
    * @Route("/edit", name="edit_event_page")
    */
    public function editEvent(): Response
    {
        return $this->render('event/edit.html.twig', [

        ]);
    }
}