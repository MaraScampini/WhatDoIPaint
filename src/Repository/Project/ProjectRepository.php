<?php

namespace App\Repository\Project;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository implements ProjectRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getProjectById(int $id): ?array
    {
        return $this->createQueryBuilder('PROJECT')
            ->select('PROJECT.name, PROJECT.description, BRAND.name AS brand, LEVEL.name AS level')
            ->leftJoin('PROJECT.brand', 'BRAND')
            ->leftJoin('PROJECT.level', 'LEVEL')
            ->leftJoin('PROJECT.projectTechniques', 'PT')
            ->leftJoin('PT.technique', 'TECHNIQUE')
            ->andWhere('PROJECT.id = :id')
            ->setParameter('id', $id)
            ->groupBy('PROJECT.id')
            ->getQuery()
            ->getSingleResult();
    }
}
