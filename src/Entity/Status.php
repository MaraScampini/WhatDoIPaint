<?php

namespace App\Entity;

use App\Repository\Status\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Element>
     */
    #[ORM\OneToMany(targetEntity: Element::class, mappedBy: 'status')]
    private Collection $elements;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'status')]
    private Collection $projects;

    /**
     * @var Collection<int, SquadStatus>
     */
    #[ORM\OneToMany(targetEntity: SquadStatus::class, mappedBy: 'status')]
    private Collection $squadStatuses;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
        $this->projects = new ArrayCollection();
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
            $element->setStatus($this);
        }

        return $this;
    }

    public function removeElement(Element $element): static
    {
        if ($this->elements->removeElement($element)) {
            // set the owning side to null (unless already changed)
            if ($element->getStatus() === $this) {
                $element->setStatus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setStatus($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getStatus() === $this) {
                $project->setStatus(null);
            }
        }

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
            $squadStatus->setStatus($this);
        }

        return $this;
    }

    public function removeSquadStatus(SquadStatus $squadStatus): static
    {
        if ($this->squadStatuses->removeElement($squadStatus)) {
            // set the owning side to null (unless already changed)
            if ($squadStatus->getStatus() === $this) {
                $squadStatus->setStatus(null);
            }
        }

        return $this;
    }
}
