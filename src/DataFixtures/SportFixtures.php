<?php

namespace App\DataFixtures;

use App\Entity\Sport;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
 
 
final class SportFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $sports = ["Chessboxing", "Tennis", "Hockey subaquatique", "Cheese rolling", "Swamp football", "Hockey sous glace"];

        foreach ($sports as $sportLabel)
        {
            $sport = new Sport();
            
            $sport->setLabel($sportLabel);
            $manager->persist($sport);
        }

        $manager->flush();
    }
}