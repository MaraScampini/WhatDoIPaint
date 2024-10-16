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

    public function getProjectsByUser(User $user, array $params): ?array
    {
        $baseQuery = $this->createQueryBuilder('USER_PROJECTS')
            ->select('
            PROJECT.id, PROJECT.name, MAX(IMAGE.url) AS image, USER_PROJECTS.id AS userProjectId, USER_PROJECTS.priority, CASE WHEN COUNT(PROJECT_UPDATE.id) > 0 THEN true ELSE false END AS updatedToday')
            ->leftJoin('USER_PROJECTS.project', 'PROJECT')
            ->leftJoin('PROJECT.images', 'IMAGE')
            ->leftJoin('PROJECT.updates', 'PROJECT_UPDATE', 'WITH', 'PROJECT_UPDATE.project = PROJECT AND PROJECT_UPDATE.date > :sod AND PROJECT_UPDATE.date < :eod')
            ->setParameter('sod', (new \DateTime())->setTime(0, 0, 0))
            ->setParameter('eod', (new \DateTime())->setTime(23, 59, 59))
            ->andWhere('USER_PROJECTS.user = :user')
            ->setParameter('user', $user);

        if(isset($params['level']) && $params['level'] !== 0) {
            $levelFilter = $params['level'];
            $baseQuery->andWhere('PROJECT.level = :level')
                ->setParameter('level', $levelFilter);
        }

        if(isset($params['technique']) && $params['technique'] !== 0) {
            $techniqueFilter = $params['technique'];
            $baseQuery->leftJoin('PROJECT.projectTechniques', 'PROJECT_TECHNIQUES')
                ->andWhere('PROJECT_TECHNIQUES.technique = :technique')
                ->setParameter('technique', $techniqueFilter);
        }

        if(isset($params['search'])) {
            $search = $params['search'];
            $baseQuery->andWhere('PROJECT.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $baseQuery
            ->orderBy('USER_PROJECTS.priority', 'DESC')
            ->addOrderBy('PROJECT.lastUpdate', 'DESC')
            ->groupBy('PROJECT.id, USER_PROJECTS.id')
            ->getQuery()
            ->getResult();

    }

}
