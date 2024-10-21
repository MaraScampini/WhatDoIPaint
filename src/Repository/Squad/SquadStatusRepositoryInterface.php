<?php

namespace App\Repository\Squad;

interface SquadStatusRepositoryInterface
{
    public function getElementsBySquad(int $squadId): ?array;
}