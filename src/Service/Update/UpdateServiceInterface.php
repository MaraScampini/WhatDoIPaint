<?php

namespace App\Service\Update;

use App\Entity\Update;

interface UpdateServiceInterface
{
    public function getUpdatesForGeneralProjectByProjectId(int $projectId): array;

    public function getPaginatedUpdatesByProjectId(int $projectId, int $page, int $limit): ?array;

    public function getUpdateInformation(int $updateId): array;

    public function createShortUpdate(int $projectId): Update;

    public function createUpdate(array $request): void;

}