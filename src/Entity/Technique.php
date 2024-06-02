<?php

namespace App\Entity;

use App\Repository\Technique\TechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechniqueRepository::class)]
class Technique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ProjectTechnique>
     */
    #[ORM\OneToMany(targetEntity: ProjectTechnique::class, mappedBy: 'technique')]
    private Collection $projectTechniques;

    public function __construct()
    {
        $this->projectTechniques = new ArrayCollection();
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
            $projectTechnique->setTechnique($this);
        }

        return $this;
    }

    public function removeProjectTechnique(ProjectTechnique $projectTechnique): static
    {
        if ($this->projectTechniques->removeElement($projectTechnique)) {
            // set the owning side to null (unless already changed)
            if ($projectTechnique->getTechnique() === $this) {
                $projectTechnique->setTechnique(null);
            }
        }

        return $this;
    }
}
