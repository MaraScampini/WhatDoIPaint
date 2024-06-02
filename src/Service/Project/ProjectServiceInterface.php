<?php

namespace App\Service\Project;

use App\Entity\Status;
use App\Entity\User;

interface ProjectServiceInterface
{
    public function createProject(array $projectData, User $user): void;
    public function addExtraUser(array $projectData): void;
}