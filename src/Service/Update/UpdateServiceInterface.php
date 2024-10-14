<?php

namespace App\Service\Update;

use App\Entity\Update;

interface UpdateServiceInterface
{
    public function getUpdatesByProjectId(int $projectId): array;
    public function getUpdateInformation(int $updateId): array;

    public function createShortUpdate(int $projectId): Update;

    public function createUpdate(array $request): void;

}