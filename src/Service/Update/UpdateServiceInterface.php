<?php

namespace App\Service\Update;

interface UpdateServiceInterface
{
    public function getUpdatesByProjectId(int $projectId): array;
    public function getUpdateInformation(int $updateId): array;

}