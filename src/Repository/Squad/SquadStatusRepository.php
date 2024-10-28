<?php

namespace App\Repository\Squad;

use App\Entity\Squad;
use App\Entity\SquadStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SquadStatus>
 * @method SquadStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method SquadStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method SquadStatus[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
            ->select('SQUAD_STATUS.amount, STATUS.id AS statusId, STATUS.name AS status')
            ->leftJoin('SQUAD_STATUS.status', 'STATUS')
            ->andWhere('SQUAD_STATUS.squad = :squad')
            ->andWhere('SQUAD_STATUS.amount > 0')
            ->setParameter('squad', $squadId)
            ->groupBy('SQUAD_STATUS.id')
            ->getQuery()
            ->getResult();
    }

    public function getFinishedElementsBySquad(Squad $squad): ?int
    {
        return $this->createQueryBuilder('SQUAD_STATUS')
            ->select('SQUAD_STATUS.amount')
            ->andWhere('SQUAD_STATUS.squad = :squad')
            ->andWhere('SQUAD_STATUS.status = :finished')
            ->setParameter('squad', $squad)
            ->setParameter('finished', 8)
            ->getQuery()
            ->getSingleScalarResult();
    }
}