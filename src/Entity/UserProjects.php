<?php

namespace App\Entity;

use App\Repository\UserProjects\UserProjectsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProjectsRepository::class)]
class UserProjects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userProjects')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userProjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $incorporationDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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

    public function getIncorporationDate(): ?\DateTimeInterface
    {
        return $this->incorporationDate;
    }

    public function setIncorporationDate(\DateTimeInterface $incorporationDate): static
    {
        $this->incorporationDate = $incorporationDate;

        return $this;
    }
}
