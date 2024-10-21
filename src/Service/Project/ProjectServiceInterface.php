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

    public function getProjectInfoById(int $projectId): array;

    public function addElementsToProject(array $elementsData): void;

    public function getElementsByProjectId(int $projectId): array;

    public function getSquadsByProjectId(int $projectId): array;

    public function getProjectGallery(int $projectId, int $page, int $limit): ?array;

    public function getSquadsAndElementsByProjectId(int $projectId): ?array;

}