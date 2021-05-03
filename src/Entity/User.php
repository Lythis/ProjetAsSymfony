<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,32})$/",
     *     message = "Votre mot de passe doit contenir un caractère spécial,
     *     une lettre minuscule, une majuscule, 8 caractères et
     *     32 caractères maximum et un chiffre."
     * )
     */
    private $plainPassword;
     /**
    * @var string
    *
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $token;
     
    /**
    * @var string
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $email;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $passwordRequestedAt;
    
        
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isEnabled;

    /**
     * @var string The hashed plainPassword
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;


    public function getIsEnabled()
    {
        return $this->isEnabled;
    }
   
    public function setIsEnabled($isEnabled)
    {
       $this->isEnabled = $isEnabled;
    }
   
    public function getId(): ?int
    {
        return $this->id;
    }

     /*
     * Get passwordRequestedAt
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /*
     * Set passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    /*
     * Set token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * @see UserInterface
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {

    }

    public function getSalt()
    {

    }

     /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return [
            
        ];
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->getIsEnabled();
    }

    
    public function getStatus(): bool
    {
        return $this->status;
    }
    
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

}
