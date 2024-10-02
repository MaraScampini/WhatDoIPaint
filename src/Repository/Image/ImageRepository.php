<?php

namespace App\Repository\Image;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }


    public function getImagesByUpdateId(int $updateId): ?array
    {
        return $this->createQueryBuilder('IMAGE')
            ->select('IMAGE.url')
            ->andWhere('IMAGE.newUpdate = :updateId')
            ->setParameter('updateId', $updateId)
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function getImagesByProjectId(int $projectId, int $page = 1, int $limit = 5): ?array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('IMAGE')
            ->select('IMAGE.url')
            ->leftJoin('IMAGE.newUpdate', 'IMAGE_UPDATE')
            ->leftJoin('IMAGE_UPDATE.elementUpdates', 'ELEMENT_UPDATES')
            ->leftJoin('ELEMENT_UPDATES.element', 'ELEMENT')
            ->leftJoin('ELEMENT_UPDATES.Squad', 'SQUAD')
            ->leftJoin('ELEMENT.project', 'ELEMENT_PROJECT')
            ->leftJoin('SQUAD.project', 'SQUAD_PROJECT')
            ->orWhere('ELEMENT_PROJECT.id = :projectId')
            ->orWhere('SQUAD_PROJECT.id = :projectId')
            ->orWhere('IMAGE.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->groupBy('IMAGE.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getSingleColumnResult();
    }
}
