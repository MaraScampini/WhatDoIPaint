<?php

namespace App\Repository\ProjectTechnique;

use App\Entity\ProjectTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectTechnique>
 * @method ProjectTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectTechnique[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectTechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectTechnique::class);
    }

}
