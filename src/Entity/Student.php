<?php

namespace App\Entity;

use DateTimeInterface;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\JoinColumn(nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\OneToOne(targetEntity=Category::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=SubscriptionEvent::class, mappedBy="student", orphanRemoval=true)
     */
    private $subscriptionEvents;

    public function __construct()
    {
        $this->subscriptionEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|SubscriptionEvent[]
     */
    public function getSubscriptionEvents(): Collection
    {
        return $this->subscriptionEvents;
    }

    public function addSubscriptionEvent(SubscriptionEvent $subscriptionEvent): self
    {
        if (!$this->subscriptionEvents->contains($subscriptionEvent)) {
            $this->subscriptionEvents[] = $subscriptionEvent;
            $subscriptionEvent->setStudent($this);
        }

        return $this;
    }

    public function removeSubscriptionEvent(SubscriptionEvent $subscriptionEvent): self
    {
        if ($this->subscriptionEvents->removeElement($subscriptionEvent)) {
            // set the owning side to null (unless already changed)
            if ($subscriptionEvent->setStudent() === $this) {
                $subscriptionEvent->setStudent(null);
            }
        }

        return $this;
    }
}
