<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Sport;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventKeyController extends AbstractController
{

    /**
    * @Route("/create/category", name="event_create_category")
    */
    public function createCategory(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("event_create_category");
        }

        return $this->render('category/create.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    /**
    * @Route("/category", name="category_admin")
    */
    public function adminCategory(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categorys = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('category/show.html.twig', [
            'categorys' => $categorys
        ]);
    }

    /**
    * @Route("/create/Sport", name="event_create_sport")
    */
    public function createSport(Request $request): Response
    {
        $sport = new Sport();

        $form = $this->createForm(CategoryType::class, $sport);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sport = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($sport);
            $manager->flush();

            return $this->redirectToRoute("event_create_category");
        }

        return $this->render('sport/create.html.twig', [
            'sportForm' => $form->createView(),
        ]);
    }

    /**
    * @Route("/sport", name="sport_admin")
    */
    public function sportShow(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $sports = $this->getDoctrine()->getRepository(Sport::class)->findAll();

        return $this->render('sport/show.html.twig', [
            'sports' => $sports
        ]);
    }

}