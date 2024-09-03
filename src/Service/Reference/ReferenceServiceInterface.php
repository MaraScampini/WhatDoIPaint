<?php

namespace App\Service\Reference;

use App\Entity\Project;

interface ReferenceServiceInterface
{
    public function createReference(array $referenceData): void;
}