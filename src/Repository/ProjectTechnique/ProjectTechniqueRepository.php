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
class ProjectTechniqueRepository extends ServiceEntityRepository implements ProjectTechniqueRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectTechnique::class);
    }

    public function getProjectTechniquesByProjectId(int $projectId): ?array
    {
        return $this->createQueryBuilder('PROJECT_TECHNIQUE')
            ->select('TECHNIQUE.name AS technique')
            ->leftJoin('PROJECT_TECHNIQUE.project', 'PROJECT')
            ->leftJoin('PROJECT_TECHNIQUE.technique', 'TECHNIQUE')
            ->andWhere('PROJECT.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getSingleColumnResult();
    }
}
