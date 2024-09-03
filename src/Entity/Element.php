<?php

namespace App\Entity;

use App\Repository\Element\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementRepository::class)]
class Element
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastUpdate = null;

    /**
     * @var Collection<int, ElementUpdate>
     */
    #[ORM\OneToMany(targetEntity: ElementUpdate::class, mappedBy: 'element')]
    private Collection $elementUpdates;

    #[ORM\ManyToOne(inversedBy: 'elements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'elements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function __construct()
    {
        $this->elementUpdates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeInterface $lastUpdate): static
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * @return Collection<int, ElementUpdate>
     */
    public function getElementUpdates(): Collection
    {
        return $this->elementUpdates;
    }

    public function addElementUpdate(ElementUpdate $elementUpdate): static
    {
        if (!$this->elementUpdates->contains($elementUpdate)) {
            $this->elementUpdates->add($elementUpdate);
            $elementUpdate->setElement($this);
        }

        return $this;
    }

    public function removeElementUpdate(ElementUpdate $elementUpdate): static
    {
        if ($this->elementUpdates->removeElement($elementUpdate)) {
            // set the owning side to null (unless already changed)
            if ($elementUpdate->getElement() === $this) {
                $elementUpdate->setElement(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }
}
