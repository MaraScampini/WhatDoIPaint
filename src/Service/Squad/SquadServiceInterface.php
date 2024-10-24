<?php

namespace App\Service\Squad;

interface SquadServiceInterface
{
    public function removeSquad(int $squadId): void;
}