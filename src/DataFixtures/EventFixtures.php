<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Sport;
use DateInterval;
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
        $sports = ["Chessboxing", "Hockey subaquatique", "Hockey sous glace", "Tennis", "E-Sport", "League of Legends"];
        $events = ["Chessboxing 1vs1 en Syrie", "Chessboxing sur glace", "Fight 2v2", "Match de tennis", "Match Amical Tokyo", "Aram LOL"];
        $descs = ["Sport caritatif pour aider Morgan à passer son BTS.", "Why not?", "UwU", "3v3", "E-Sport à Tokyo sur un terrain de tennis. Tout déserteur sera exécuté.", "Aram 5vs5 pas le droit à Veigar."];

        $dateNow = new DateTime("NOW");
        $dateEnd = new DateTime("NOW");
        $dateEnd->add(new DateInterval('P7D'));

        foreach ($events as $key => $eventName)
        {
            $event = new Event();
            $sport = new Sport();

            $sport->setLabel($sports[$key]);
            $manager->persist($sport);

            $event->setName($eventName);
            $event->setDescription($descs[$key]);
            $event->setNumberPlace(rand(1, 100));
            $event->setDate($dateNow);
            $event->setDateEnd($dateEnd);
            $event->setImage("default.jpg");
            $event->setThumbnail("default.jpg");
            $event->setSport($sport);
            $manager->persist($event);
        }

        $manager->flush();
    }
}