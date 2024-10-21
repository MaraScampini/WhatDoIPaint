<?php

namespace App\Repository\Streak;

use App\Entity\Streak;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Streak>
 */
class StreakRepository extends ServiceEntityRepository implements StreakRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Streak::class);
    }
}