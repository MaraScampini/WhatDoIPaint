<?php

namespace App\Repository\Element;

use App\Entity\Element;
use App\Entity\Squad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Element>
 * @method Element|null find($id, $lockMode = null, $lockVersion = null)
 * @method Element|null findOneBy(array $criteria, array $orderBy = null)
 * @method Element[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementRepository extends ServiceEntityRepository implements ElementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Element::class);
    }

    public function getElementsByProjectId(int $projectId): ?array
    {
        return $this->createQueryBuilder('ELEMENT')
            ->select('ELEMENT.name, ELEMENT.lastUpdate, STATUS.name AS status')
            ->leftJoin('ELEMENT.project', 'PROJECT')
            ->leftJoin('ELEMENT.status', 'STATUS')
            ->andWhere('PROJECT.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->andWhere('ELEMENT.Squad IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function elementsByProjectIdSelector(int $projectId): ?array
    {
        return $this->createQueryBuilder('ELEMENT')
            ->select('ELEMENT.id, ELEMENT.name as label')
            ->leftJoin('ELEMENT.project', 'PROJECT')
            ->andWhere('PROJECT.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->andWhere('ELEMENT.Squad IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function getElementsBySquad(int $squadId): ?array
    {
        return $this->createQueryBuilder('ELEMENT')
            ->select('STATUS.name AS status, COUNT(STATUS.name) AS amount')
            ->leftJoin('ELEMENT.status', 'STATUS')
            ->andWhere('ELEMENT.Squad = :squad')
            ->setParameter('squad', $squadId)
            ->groupBy('STATUS.id')
            ->getQuery()
            ->getResult();
    }

}
