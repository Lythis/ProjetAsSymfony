<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PendingUserRepository::class)
 * @ORM\Table(name="`pendingUser`")
 */
class PendingUser 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Student::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $student;
    
     
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
    */
    private $dateAdd;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPending;
    
     
    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $dateAnswered;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent($student)
    {
        $this->student = $student;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getDateAdd(): ?DateTime
    {
        return $this->dateAdd;
    }

    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getDateAnswered(): ?DateTime
    {
        return $this->dateAnswerd;
    }

    public function setDateAnswered($dateAnswered)
    {
        $this->dateAnswered = $dateAnswered;
        return $this;
    }

    public function isPending()
    {
        return $this->isPending;
    }

}
