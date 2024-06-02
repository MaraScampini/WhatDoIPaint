<?php

namespace App\Repository\Brand;

use App\Entity\Brand;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Brand>
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * /
 */
class BrandRepository extends ServiceEntityRepository implements BrandRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    public function getBrandsSelector(User $user): array
    {
        return $this->createQueryBuilder('BRAND')
            ->select('BRAND.id', 'BRAND.name')
            ->andWhere('BRAND.isGeneric = true')
            ->orWhere('BRAND.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }


}
