<?php

namespace App\Repository\Squad;

use App\Entity\Squad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Squad>
 */
class SquadRepository extends ServiceEntityRepository implements SquadRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Squad::class);
    }

    public function getSquadsByProjectId(int $projectId): ?array
    {
        return $this->createQueryBuilder('SQUAD')
            ->select('SQUAD.id, SQUAD.name, SQUAD.lastUpdate, COUNT(ELEMENT.id) AS amount')
            ->leftJoin('SQUAD.project', 'PROJECT')
            ->leftJoin('SQUAD.elements', 'ELEMENT')
            ->andWhere('PROJECT.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->groupBy('SQUAD.id')
            ->getQuery()
            ->getResult();
    }

    public function squadsByProjectIdSelector(int $projectId): ?array
    {
        return $this->createQueryBuilder('SQUAD')
            ->select('SQUAD.id, SQUAD.name as label')
            ->leftJoin('SQUAD.project', 'PROJECT')
            ->andWhere('PROJECT.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->groupBy('SQUAD.id')
            ->getQuery()
            ->getResult();

    }
}
