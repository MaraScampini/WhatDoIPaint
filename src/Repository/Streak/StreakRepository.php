<?php

namespace App\Repository\Streak;

use App\Entity\Streak;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Streak>
 * @method Streak|null find($id, $lockMode = null, $lockVersion = null)
 * @method Streak|null findOneBy(array $criteria, array $orderBy = null)
 * @method Streak[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * /
 */
class StreakRepository extends ServiceEntityRepository implements StreakRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Streak::class);
    }
}