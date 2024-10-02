<?php

namespace App\Service\Update;

interface UpdateServiceInterface
{
    public function getUpdatesByProjectId(int $projectId): array;

}