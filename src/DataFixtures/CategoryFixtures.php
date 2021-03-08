<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
 
 
final class CategoryFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $categories = ["Athlétiques", "Gymniques", "Aquatiques", "Nautiques", "Jeu", "Glace"];

        foreach ($categories as $categoryLabel)
        {
            $category = new Category();
            
            $category->setLabel($categoryLabel);
            $manager->persist($category);
        }

        $manager->flush();
    }
}