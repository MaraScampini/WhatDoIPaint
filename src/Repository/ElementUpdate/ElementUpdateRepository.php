<?php

namespace App\Repository\ElementUpdate;

use App\Entity\ElementUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ElementUpdate>
 * @method ElementUpdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElementUpdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElementUpdate[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementUpdateRepository extends ServiceEntityRepository implements ElementUpdateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElementUpdate::class);
    }

    public function getUpdatesByProjectId(int $projectId): ?array
    {
        return $this->createQueryBuilder('UPDATE_ELEMENT')
            ->select('NEW_UPDATE.id, NEW_UPDATE.title, NEW_UPDATE.description, NEW_UPDATE.date')
            ->leftJoin('UPDATE_ELEMENT.element', 'ELEMENT')
            ->leftJoin('UPDATE_ELEMENT.Squad', 'SQUAD')
            ->leftJoin('UPDATE_ELEMENT.newUpdate', 'NEW_UPDATE')
            ->leftJoin('ELEMENT.project', 'PROJECT_ELEMENT')
            ->leftJoin('SQUAD.project', 'PROJECT_SQUAD')
            ->orWhere('PROJECT_ELEMENT.id = :projectId')
            ->orWhere('PROJECT_SQUAD.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->groupBy('NEW_UPDATE.id')
            ->getQuery()
            ->getResult();
    }

    public function getElementsAndSquadsByUpdateId(int $updateId): ?array
    {
        return $this->createQueryBuilder('UPDATE_ELEMENT')
            ->select('COALESCE(ELEMENT.name, SQUAD.name) AS name')
            ->leftJoin('UPDATE_ELEMENT.element', 'ELEMENT')
            ->leftJoin('UPDATE_ELEMENT.Squad', 'SQUAD')
            ->andWhere('UPDATE_ELEMENT.newUpdate = :updateId')
            ->setParameter('updateId', $updateId)
            ->getQuery()
            ->getSingleColumnResult();
    }

}
