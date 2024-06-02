<?php

namespace App\Service\Project;

use App\Entity\Brand;
use App\Entity\Level;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\UserProjects;
use App\Exception\EntityNotFoundException;
use App\Repository\Brand\BrandRepositoryInterface;
use App\Repository\Level\LevelRepositoryInterface;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\Status\StatusRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\UserProjects\UserProjectsRepositoryInterface;

class ProjectRepositoryService
{
    public function __construct(
        private readonly StatusRepositoryInterface $statusRE,
        private readonly LevelRepositoryInterface $levelRE,
        private readonly BrandRepositoryInterface $brandRE,
        private readonly UserRepositoryInterface $userRE,
        private readonly ProjectRepositoryInterface $projectRE,
        private readonly UserProjectsRepositoryInterface $userProjectsRE
    ) {}

    /**
     * @throws EntityNotFoundException
     */
    public function getProject(int $projectId): Project
    {
        $project = $this->projectRE->find($projectId);
        if(!$project instanceof Project) throw new EntityNotFoundException('Project');
        return $project;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getUser(int $userId): User
    {
        $user = $this->userRE->find($userId);
        if(!$user instanceof User) throw new EntityNotFoundException('User');
        return $user;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getStatus(int $statusId): Status
    {
        $status = $this->statusRE->find($statusId);
        if(!$status instanceof Status) throw new EntityNotFoundException('Status');
        return $status;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getLevel(int $levelId): Level
    {
        $level = $this->levelRE->find($levelId);
        if(!$level instanceof Level) throw new EntityNotFoundException('Level');
        return $level;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getBrand(int $brandId): Brand
    {
        $brand = $this->brandRE->find($brandId);
        if(!$brand instanceof Brand) throw new EntityNotFoundException('Brand');
        return $brand;
    }

    public function checkIfUserAlreadyInProject(User $user, Project $project): bool
    {
        $userProject = $this->userProjectsRE->findOneBy(['user' => $user, 'project' => $project]);
        if($userProject instanceof UserProjects) return true;
        return false;
    }
}