<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Student;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // Elèves
        $studentList = [];
        $userList = [];
        
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $student = new Student();

            $user->setPassword($this->encoder->encodePassword($user, 12345))
            ->setStatus(1);

            
            array_push($userList, $user);
            $manager->persist($user);

            $student->setFirstName('Jean'.($i + 1))
            ->setLastName('Louis'.($i + 1))
            ->setBirthDate($this->getRandomDate())
            ->setUser($user);
            array_push($studentList, $student);

            $manager->persist($student);
        }

        foreach ($studentList as $key => $student)
        {
            $user = $userList[$key];
            $user->setEmail($student->getFirstName().$student->getLastName().'@gmail.com')
            ->setRole('student');

            $manager->persist($user);
        }

        // Admins
        for ($i = 0; $i < 5; $i++) {
            $admin = new User();

            $admin->setEmail('user-admin-'.($i + 1).'@gmail.com')
            ->setPassword($this->encoder->encodePassword($admin, 'root'))
            ->setRole('admin')
            ->setStatus(1);


            $manager->persist($admin);
        }

        $manager->flush();
    }

    public function getRandomDate()
    {
        $year = random_int(1990, 2000);
        $month = random_int(1, 12);
        $day = random_int(1, 25);

        return new DateTime($year.'-'.$month.'-'.$day);
    }
}