<?php

namespace App\Service\Squad;

use App\Entity\Squad;

interface SquadStatusServiceInterface
{
    public function updateSquadStatuses(Squad $squad, array $elements): void;
}