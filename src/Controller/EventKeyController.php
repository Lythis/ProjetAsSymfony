<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Category;
use App\Entity\Sport;
use App\Form\SportType;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventKeyController extends AbstractController
{

    /**
    * @Route("/create/category", name="category_create_page")
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

            return $this->redirectToRoute("category_admin");
        }

        return $this->render('category/create.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    /**
    * @Route("/category", name="category_admin")
    */
    public function adminCategory(): Response
    {
        $categorys = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('category/show.html.twig', [
            'categorys' => $categorys
        ]);
    }

    /**
    * @Route("/create/sport", name="sport_create_page")
    */
    public function createSport(Request $request): Response
    {
        $sport = new Sport();

        $form = $this->createForm(SportType::class, $sport);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sport = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($sport);
            $manager->flush();

            return $this->redirectToRoute("sport_admin");
        }

        return $this->render('sport/create.html.twig', [
            'sportForm' => $form->createView(),
        ]);
    }

    /**
    * @Route("/sport", name="sport_admin")
    */
    public function sportShow(): Response
    {
        $sports = $this->getDoctrine()->getRepository(Sport::class)->findAll();

        return $this->render('sport/show.html.twig', [
            'sports' => $sports
        ]);
    }

    /**
    * @Route("/category/delete/{id}", name="category_delete")
    */
    public function categoryDelete(Category $category): Response
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(
            array('category' => $category->getId()),
        );

        $manager = $this->getDoctrine()->getManager();
        foreach ($events as $event)
        {
            $event->setCategory(null);

            $manager->persist($event);
        }
        $manager->flush();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute("category_admin");
    }

    /**
    * @Route("/sport/delete/{id}", name="sport_delete")
    */
    public function sportDelete(Sport $sport): Response
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(
            array('sport' => $sport->getId()),
        );

        $manager = $this->getDoctrine()->getManager();
        foreach ($events as $event)
        {
            $event->setSport(null);

            $manager->persist($event);
        }
        $manager->flush();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sport);
        $entityManager->flush();

        return $this->redirectToRoute("sport_admin");
    }
}