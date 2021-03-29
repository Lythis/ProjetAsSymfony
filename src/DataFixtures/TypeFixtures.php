<?php

namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\Sport;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class TypeFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $types = ["Caritatif", "Payant", "Paralympique", "Compétition", "Match"];

        foreach ($types as $typeLabel)
        {
            $type = new Type();
            
            $type->setLabel($typeLabel);
            $manager->persist($type);
        }

        $manager->flush();
    }
}