<?php

namespace App\Entity;

use App\Repository\ElementUpdate\ElementUpdateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementUpdateRepository::class)]
class ElementUpdate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'elementUpdates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Element $element = null;

    #[ORM\ManyToOne(inversedBy: 'elementUpdates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Update $newUpdate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): static
    {
        $this->element = $element;

        return $this;
    }

    public function getNewUpdate(): ?Update
    {
        return $this->newUpdate;
    }

    public function setNewUpdate(?Update $newUpdate): static
    {
        $this->newUpdate = $newUpdate;

        return $this;
    }
}
