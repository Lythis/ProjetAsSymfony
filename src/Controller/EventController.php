<?php
// src/Controller/TestCSSController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
+   * @Route("/event/create")
+   */
    public function createEvent(): Response
    {
        return $this->render('event\create.html.twig', [
            
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
}