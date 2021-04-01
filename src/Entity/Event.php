<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberPlace;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $sport;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class,inversedBy="event",cascade={"persist", "refresh"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=SubscriptionEvent::class, mappedBy="event", orphanRemoval=true)
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate( $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNumberPlace(): ?int
    {
        return $this->numberPlace;
    }

    public function setNumberPlace(int $numberPlace): self
    {
        $this->numberPlace = $numberPlace;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $subscriptionEvent->setEvent($this);
        }

        return $this;
    }

    public function removeSubscriptionEvent(SubscriptionEvent $subscriptionEvent): self
    {
        if ($this->subscriptionEvents->removeElement($subscriptionEvent)) {
            // set the owning side to null (unless already changed)
            if ($subscriptionEvent->setEvent() === $this) {
                $subscriptionEvent->setEvent(null);
            }
        }

        return $this;
    }
}
