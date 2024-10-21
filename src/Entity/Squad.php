<?php

namespace App\Entity;

use App\Repository\Squad\SquadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SquadRepository::class)]
class Squad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'squads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    /**
     * @var Collection<int, ElementUpdate>
     */
    #[ORM\OneToMany(targetEntity: ElementUpdate::class, mappedBy: 'Squad')]
    private Collection $elementUpdates;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastUpdate = null;

    /**
     * @var Collection<int, SquadStatus>
     */
    #[ORM\OneToMany(targetEntity: SquadStatus::class, mappedBy: 'squad')]
    private Collection $squadStatuses;

    #[ORM\Column]
    private ?int $amount = null;

    public function __construct()
    {
        $this->elementUpdates = new ArrayCollection();
        $this->squadStatuses = new ArrayCollection();
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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

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
            $elementUpdate->setSquad($this);
        }

        return $this;
    }

    public function removeElementUpdate(ElementUpdate $elementUpdate): static
    {
        if ($this->elementUpdates->removeElement($elementUpdate)) {
            // set the owning side to null (unless already changed)
            if ($elementUpdate->getSquad() === $this) {
                $elementUpdate->setSquad(null);
            }
        }

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
     * @return Collection<int, SquadStatus>
     */
    public function getSquadStatuses(): Collection
    {
        return $this->squadStatuses;
    }

    public function addSquadStatus(SquadStatus $squadStatus): static
    {
        if (!$this->squadStatuses->contains($squadStatus)) {
            $this->squadStatuses->add($squadStatus);
            $squadStatus->setSquad($this);
        }

        return $this;
    }

    public function removeSquadStatus(SquadStatus $squadStatus): static
    {
        if ($this->squadStatuses->removeElement($squadStatus)) {
            // set the owning side to null (unless already changed)
            if ($squadStatus->getSquad() === $this) {
                $squadStatus->setSquad(null);
            }
        }

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
