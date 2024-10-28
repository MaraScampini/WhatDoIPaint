<?php

namespace App\Service\Streak;

use App\Entity\Streak;
use App\Entity\User;

interface StreakServiceInterface
{
    public function createOrUpdateStreak(User $user);
}