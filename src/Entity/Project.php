<?php

namespace App\Entity;

use App\Repository\Project\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastUpdate = null;

    #[ORM\Column]
    private ?bool $isPriority = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, ProjectTechnique>
     */
    #[ORM\OneToMany(targetEntity: ProjectTechnique::class, mappedBy: 'project')]
    private Collection $projectTechniques;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $level = null;

    /**
     * @var Collection<int, Reference>
     */
    #[ORM\OneToMany(targetEntity: Reference::class, mappedBy: 'project')]
    private Collection $reference;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Brand $brand = null;

    /**
     * @var Collection<int, UserProjects>
     */
    #[ORM\OneToMany(targetEntity: UserProjects::class, mappedBy: 'project')]
    private Collection $userProjects;

    /**
     * @var Collection<int, Element>
     */
    #[ORM\OneToMany(targetEntity: Element::class, mappedBy: 'project')]
    private Collection $elements;

    public function __construct()
    {
        $this->projectTechniques = new ArrayCollection();
        $this->reference = new ArrayCollection();
        $this->userProjects = new ArrayCollection();
        $this->elements = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

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

    public function isPriority(): ?bool
    {
        return $this->isPriority;
    }

    public function setPriority(bool $isPriority): static
    {
        $this->isPriority = $isPriority;

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

    /**
     * @return Collection<int, ProjectTechnique>
     */
    public function getProjectTechniques(): Collection
    {
        return $this->projectTechniques;
    }

    public function addProjectTechnique(ProjectTechnique $projectTechnique): static
    {
        if (!$this->projectTechniques->contains($projectTechnique)) {
            $this->projectTechniques->add($projectTechnique);
            $projectTechnique->setProject($this);
        }

        return $this;
    }

    public function removeProjectTechnique(ProjectTechnique $projectTechnique): static
    {
        if ($this->projectTechniques->removeElement($projectTechnique)) {
            // set the owning side to null (unless already changed)
            if ($projectTechnique->getProject() === $this) {
                $projectTechnique->setProject(null);
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

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): static
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection<int, Reference>
     */
    public function getReference(): Collection
    {
        return $this->reference;
    }

    public function addReference(Reference $reference): static
    {
        if (!$this->reference->contains($reference)) {
            $this->reference->add($reference);
            $reference->setProject($this);
        }

        return $this;
    }

    public function removeReference(Reference $reference): static
    {
        if ($this->reference->removeElement($reference)) {
            // set the owning side to null (unless already changed)
            if ($reference->getProject() === $this) {
                $reference->setProject(null);
            }
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, UserProjects>
     */
    public function getUserProjects(): Collection
    {
        return $this->userProjects;
    }

    public function addUserProject(UserProjects $userProject): static
    {
        if (!$this->userProjects->contains($userProject)) {
            $this->userProjects->add($userProject);
            $userProject->setProject($this);
        }

        return $this;
    }

    public function removeUserProject(UserProjects $userProject): static
    {
        if ($this->userProjects->removeElement($userProject)) {
            // set the owning side to null (unless already changed)
            if ($userProject->getProject() === $this) {
                $userProject->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Element>
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(Element $element): static
    {
        if (!$this->elements->contains($element)) {
            $this->elements->add($element);
            $element->setProject($this);
        }

        return $this;
    }

    public function removeElement(Element $element): static
    {
        if ($this->elements->removeElement($element)) {
            // set the owning side to null (unless already changed)
            if ($element->getProject() === $this) {
                $element->setProject(null);
            }
        }

        return $this;
    }


}
