<?php

namespace App\Service\Project;

use App\Entity\Project;
use App\Entity\User;

interface ProjectServiceInterface
{
    public function createProject(array $projectData, User $user): void;
    public function addExtraUser(array $projectData): void;
    public function editProject(array $newProjectData): void;
    public function togglePriority(string $projectId, User $user): void;
}