<?php

namespace App\Repository\UserProjects;

use App\Entity\User;
use App\Entity\UserProjects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProjects>
 * @method UserProjects|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProjects|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProjects[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProjectsRepository extends ServiceEntityRepository implements UserProjectsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProjects::class);
    }

    public function getProjectsByUser(User $user): ?array
    {
        return $this->createQueryBuilder('USER_PROJECTS')
            ->select('
            PROJECT.id, PROJECT.name, PROJECT.image, PROJECT.isPriority')
            ->leftJoin('USER_PROJECTS.project', 'PROJECT')
            ->andWhere('USER_PROJECTS.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

}
