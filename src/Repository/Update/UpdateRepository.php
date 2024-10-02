<?php

namespace App\Repository\Update;

use App\Entity\Update;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Update>
 * @method Update|null find($id, $lockMode = null, $lockVersion = null)
 * @method Update|null findOneBy(array $criteria, array $orderBy = null)
 * @method Update[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpdateRepository extends ServiceEntityRepository implements UpdateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Update::class);
    }

    public function getUpdatesByProjectId(int $projectId, int $page = 1, int $limit = 5): ?array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('NEW_UPDATE')
            ->select('NEW_UPDATE.id, NEW_UPDATE.title, NEW_UPDATE.description, NEW_UPDATE.date')
            ->andWhere('NEW_UPDATE.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->groupBy('NEW_UPDATE.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
