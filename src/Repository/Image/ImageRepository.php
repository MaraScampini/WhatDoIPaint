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
}
