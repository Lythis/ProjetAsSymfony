<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestCSSController extends AbstractController
{
    /**
+   * @Route("/testcss")
+   */
    public function Testcss(): Response
    {
        return $this->render('testcss.html.twig', [
            
        ]);
    }
}