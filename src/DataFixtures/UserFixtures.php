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
        
    for ($i = 1; $i < 10; $i++) {
            $student = new Student();
            
            $student
                ->setFirstName('jean'.$i)
                ->setLastName('louis'.$i)
                ->setBirthDate(new DateTime('2000-04-02'));
        }

        $manager->flush();

        for ($i = 1; $i < 2; $i++) {
            $user = new User();
            $password = '123';

            $user
                ->setEmail('user-'.$i.'@gmail.com');
  

            // Encode le mot de passe et l'insère dans le champ "password".
            $user->setPassword($this->encoder->encodePassword($user, $password));
            $manager->persist($user);
        }

        $manager->flush();
    }
}