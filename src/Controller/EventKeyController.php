<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Category;
use App\Entity\Sport;
use App\Entity\Type;
use App\Form\SportType;
use App\Form\CategoryType;
use App\Form\TypeType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class EventKeyController extends AbstractController
{

    /**
    * @Route("/create/category", name="category_create_page")
    */
    public function createCategory(Request $request): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $category = new Category();

            $form = $this->createForm(CategoryType::class, $category);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $category = $form->getData();

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($category);
                $manager->flush();

                return $this->redirectToRoute("category_admin", [
                    'message' => 'Catégorie '.$category->getLabel().' créée avec succès.',
                ]);
            }

            return $this->render('category/create.html.twig', [
                'categoryForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/category", name="category_admin")
    */
    public function adminCategory(Request $request): Response
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

            $categorys = $this->getDoctrine()->getRepository(Category::class)->findAll();

            return $this->render('category/show.html.twig', [
                'categorys' => $categorys
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/create/sport", name="sport_create_page")
    */
    public function createSport(Request $request): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $sport = new Sport();

            $form = $this->createForm(SportType::class, $sport);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $sport = $form->getData();

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($sport);
                $manager->flush();

                return $this->redirectToRoute("sport_admin", [
                    'message' => 'Sport '.$sport->getLabel().' créé avec succès.',
                ]);
            }

            return $this->render('sport/create.html.twig', [
                'sportForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/sport", name="sport_admin")
    */
    public function adminSport(Request $request): Response
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

            $sports = $this->getDoctrine()->getRepository(Sport::class)->findAll();

            return $this->render('sport/show.html.twig', [
                'sports' => $sports
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/create/type", name="type_create_page")
    */
    public function createType(Request $request): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $type = new Type();

            $form = $this->createForm(TypeType::class, $type);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $type = $form->getData();

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($type);
                $manager->flush();

                return $this->redirectToRoute("type_admin", [
                    'message' => 'Type '.$type->getLabel().' créé avec succès.',
                ]);
            }

            return $this->render('type/create.html.twig', [
                'typeForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/type", name="type_admin")
    */
    public function adminType(Request $request): Response
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

            $types = $this->getDoctrine()->getRepository(Type::class)->findAll();

            // TODO : find all types being used by at least one to prevent them being deleted

            return $this->render('type/show.html.twig', [
                'types' => $types
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/category/delete/{id}", name="category_delete")
    */
    public function categoryDelete(Category $category): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

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

            return $this->redirectToRoute("category_admin", [
                'message' => 'Catégorie '.$category->getLabel().' supprimée avec succès.',
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/category/edit/{id}", name="category_edit")
    */
    public function categoryEdit(Category $category, Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $form = $this->createForm(CategoryType::class, $category);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($category);
                $em->flush();

                return $this->redirectToRoute("category_admin", [
                    'message' => 'Catégorie '.$category->getLabel().' modifiée avec succès.',
                ]);
            }

            return $this->render('category/create.html.twig', [
                'categoryForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/sport/delete/{id}", name="sport_delete")
    */
    public function sportDelete(Sport $sport): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

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

            return $this->redirectToRoute("sport_admin", [
                'message' => 'Sport '.$sport->getLabel().' supprimé avec succès.',
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/sport/edit/{id}", name="sport_edit")
    */
    public function sportEdit(Sport $sport, Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $form = $this->createForm(SportType::class, $sport);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($sport);
                $em->flush();

                return $this->redirectToRoute("sport_admin", [
                    'message' => 'Sport '.$sport->getLabel().' modifié avec succès.',
                ]);
            }

            return $this->render('sport/create.html.twig', [
                'sportForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/type/delete/{id}", name="type_delete")
    */
    public function typeDelete(Type $type): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($type);
            $entityManager->flush();

            return $this->redirectToRoute("type_admin", [
                'message' => 'Type '.$type->getLabel().' supprimé avec succès.',
            ]);
        }

        return $this->redirectToRoute("app_login");
    }

    /**
    * @Route("/type/edit/{id}", name="type_edit")
    */
    public function typeEdit(Type $type, Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser())
        {
            if ($this->getUser()->getRole() !== 'admin')
            {
                return $this->redirectToRoute("event_show");
            }

            $form = $this->createForm(TypeType::class, $type);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($type);
                $em->flush();

                return $this->redirectToRoute("type_admin", [
                    'message' => 'Type '.$type->getLabel().' modifié avec succès.',
                ]);
            }

            return $this->render('type/create.html.twig', [
                'typeForm' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute("app_login");
    }
}