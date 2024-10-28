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

        $galleryQuery = $this->createQueryBuilder('IMAGE')
            ->select('IMAGE.url, IMAGE_UPDATE.date, IMAGE_UPDATE.id AS updateId')
            ->leftJoin('IMAGE.newUpdate', 'IMAGE_UPDATE')
            ->leftJoin('IMAGE_UPDATE.elementUpdates', 'ELEMENT_UPDATES')
            ->leftJoin('ELEMENT_UPDATES.element', 'ELEMENT')
            ->leftJoin('ELEMENT_UPDATES.Squad', 'SQUAD')
            ->leftJoin('ELEMENT.project', 'ELEMENT_PROJECT')
            ->leftJoin('SQUAD.project', 'SQUAD_PROJECT')
            ->orWhere('ELEMENT_PROJECT.id = :projectId')
            ->orWhere('SQUAD_PROJECT.id = :projectId')
            ->orWhere('IMAGE_UPDATE.project = :projectId')
            ->setParameter('projectId', $projectId);

        $countQuery = clone($galleryQuery);

        $total = $countQuery->select('COUNT(IMAGE.id)')->getQuery()->getSingleScalarResult();

        $gallery = $galleryQuery
            ->groupBy('IMAGE.id')
            ->orderBy('IMAGE_UPDATE.date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return [
            'total' => $total,
            'data' => $gallery
        ];
    }

    public function getImagesForGeneralProjectEndpoint(int $projectId): ?array
    {
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
            ->orWhere('IMAGE_UPDATE.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->groupBy('IMAGE.id')
            ->orderBy('IMAGE_UPDATE.date', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function getProjectCoverImage(int $projectId): array
    {
        return $this->createQueryBuilder('IMAGE')
            ->select('IMAGE.url')
            ->andWhere('IMAGE.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getSingleColumnResult();
    }
}
