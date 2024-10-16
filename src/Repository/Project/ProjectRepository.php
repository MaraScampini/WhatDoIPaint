<?php

namespace App\Repository\Project;

use App\Entity\Project;
use App\Entity\User;
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

    public function getProjectBasicInfoById(int $id): ?array
    {
        return $this->createQueryBuilder('PROJECT')
            ->select('PROJECT.id, PROJECT.name, PROJECT.description, BRAND.name AS brand, LEVEL.name AS level')
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

    public function getRandomProject(array $filters, User $user): ?array
    {
        $projectQuery = $this->createQueryBuilder('PROJECT')
            ->select('PROJECT.id, PROJECT.name, PROJECT.lastUpdate')
            ->leftJoin('PROJECT.userProjects', 'USER_PROJECT', 'WITH', 'USER_PROJECT.project = PROJECT.id')
            ->andWhere('USER_PROJECT.user = :user')
            ->setParameter('user', $user);

        if(isset($filters['level'])) {
            $levelFilter = $filters['level'];
            $projectQuery->andWhere('PROJECT.level = :level')
                ->setParameter('level', $levelFilter);
        }

        if(isset($filters['status'])) {
            $statusFilter = $filters['status'];
            $projectQuery->andWhere('PROJECT.status = :status')
                ->setParameter('status', $statusFilter);
        }

        if(isset($filters['technique'])) {
            $techniqueFilter = $filters['technique'];
            $projectQuery
                ->leftJoin('PROJECT.projectTechniques', 'PROJECT_TECHNIQUES', 'WITH', 'PROJECT_TECHNIQUES.project = PROJECT.id')
                ->andWhere('PROJECT_TECHNIQUES.technique = :technique')
                ->setParameter('technique', $techniqueFilter);
        }

        if(isset($filters['priority'])) {
            $priorityFilter = $filters['priority'];
            $projectQuery->andWhere('USER_PROJECT.priority = :priority')
                ->setParameter('priority', $priorityFilter);
        }

        return $projectQuery->orderBy('RAND()')->setMaxResults(1)->getQuery()->getOneOrNullResult();
    }
}
