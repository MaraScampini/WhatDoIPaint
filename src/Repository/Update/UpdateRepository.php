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

        $updatesQuery = $this->createQueryBuilder('NEW_UPDATE')
            ->select('NEW_UPDATE.id, NEW_UPDATE.title, NEW_UPDATE.description, NEW_UPDATE.date')
            ->andWhere('NEW_UPDATE.project = :projectId')
            ->setParameter('projectId', $projectId);

        $countQuery = clone($updatesQuery);

        $total = $countQuery->select('COUNT(NEW_UPDATE.id)')->getQuery()->getSingleScalarResult();

        $updates = $updatesQuery
            ->groupBy('NEW_UPDATE.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('NEW_UPDATE.date', 'DESC')
            ->getQuery()
            ->getResult();

        return [
            'total' => $total,
            'data' => $updates
        ];
    }

    public function getUpdatesForGeneralProjectEndpoint(int $projectId): ?array
    {
        return $this->createQueryBuilder('NEW_UPDATE')
            ->select('NEW_UPDATE.id, NEW_UPDATE.title, NEW_UPDATE.description, NEW_UPDATE.date')
            ->andWhere('NEW_UPDATE.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->groupBy('NEW_UPDATE.id')
            ->setMaxResults(5)
            ->orderBy('NEW_UPDATE.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getUpdateInformation(int $updateId): ?array
    {
        return $this->createQueryBuilder('NEW_UPDATE')
            ->select('NEW_UPDATE.id, NEW_UPDATE.title, NEW_UPDATE.date, NEW_UPDATE.description')
            ->andWhere('NEW_UPDATE.id = :updateId')
            ->setParameter('updateId', $updateId)
            ->getQuery()
            ->getSingleResult();
    }

}
