<?php

namespace App\Service\Reference;

use App\Entity\Project;

interface ReferenceServiceInterface
{
    public function createReferences(array $referenceData): void;
}