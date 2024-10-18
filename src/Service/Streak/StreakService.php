<?php

namespace App\Service\Streak;

use App\Entity\Streak;
use App\Entity\User;
use App\Repository\Streak\StreakRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class StreakService implements StreakServiceInterface
{

    public function __construct(
        private readonly StreakRepositoryInterface $streakRepository,
        private readonly EntityManagerInterface $em
    )
    {}
    public function createOrUpdateStreak(User $user): int
    {
        $currentStreak = $this->streakRepository->findOneBy(['user' => $user], ['id' => 'DESC']);

        if($currentStreak) {
            $lastUpdateDate = $currentStreak->getLastUpdateDate();
            $today = new \DateTime();
            $streakCount = $currentStreak->getStreakCount();

            if($lastUpdateDate->diff($today)->days == 1) {
                $currentStreak->setLastUpdateDate($today);
                $currentStreak->setStreakCount($currentStreak->getStreakCount() + 1);
                $streakCount = $currentStreak->getStreakCount();
            } else if($lastUpdateDate->diff($today)->days > 1) {
                $newStreak = $this->createStreak($user);
                $streakCount = $newStreak->getStreakCount();
            }
        } else {
            $newStreak = $this->createStreak($user);
            $streakCount = $newStreak->getStreakCount();
        }

        return $streakCount;
    }

    private function createStreak(User $user): Streak
    {
        $currentDate = new \DateTime();

        $newStreak = new Streak();
        $newStreak->setUser($user)
            ->setStartDate($currentDate)
            ->setLastUpdateDate($currentDate)
            ->setStreakCount(1);
        $this->em->persist($newStreak);

        return $newStreak;
    }
}