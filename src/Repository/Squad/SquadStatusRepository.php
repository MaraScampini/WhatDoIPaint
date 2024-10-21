<?php

namespace App\Repository\Squad;

use App\Entity\SquadStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SquadStatus>
 */
class SquadStatusRepository extends ServiceEntityRepository implements SquadStatusRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SquadStatus::class);
    }

    public function getElementsBySquad(int $squadId): ?array
    {
        return $this->createQueryBuilder('SQUAD_STATUS')
            ->select('SQUAD_STATUS.amount, STATUS.name AS status')
            ->leftJoin('SQUAD_STATUS.status', 'STATUS')
            ->andWhere('SQUAD_STATUS.squad = :squad')
            ->setParameter('squad', $squadId)
            ->groupBy('SQUAD_STATUS.id')
            ->getQuery()
            ->getResult();
    }

}