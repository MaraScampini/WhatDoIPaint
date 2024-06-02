<?php

namespace App\Entity;

use App\Repository\Update\UpdateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UpdateRepository::class)]
#[ORM\Table(name: '`update`')]
class Update
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastUpdate = null;

    /**
     * @var Collection<int, ElementUpdate>
     */
    #[ORM\OneToMany(targetEntity: ElementUpdate::class, mappedBy: 'newUpdate')]
    private Collection $elementUpdates;

    public function __construct()
    {
        $this->elementUpdates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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
            $elementUpdate->setNewUpdate($this);
        }

        return $this;
    }

    public function removeElementUpdate(ElementUpdate $elementUpdate): static
    {
        if ($this->elementUpdates->removeElement($elementUpdate)) {
            // set the owning side to null (unless already changed)
            if ($elementUpdate->getNewUpdate() === $this) {
                $elementUpdate->setNewUpdate(null);
            }
        }

        return $this;
    }
}
