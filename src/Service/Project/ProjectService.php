<?php

namespace App\Service\Project;

use App\Entity\Brand;
use App\Entity\Level;
use App\Entity\Project;
use App\Entity\ProjectTechnique;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\UserProjects;
use App\Exception\CustomMessageException;
use App\Exception\EntityNotFoundException;
use App\Repository\Brand\BrandRepositoryInterface;
use App\Repository\Element\ElementRepositoryInterface;
use App\Repository\ElementUpdate\ElementUpdateRepositoryInterface;
use App\Repository\Image\ImageRepositoryInterface;
use App\Repository\Level\LevelRepositoryInterface;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\ProjectTechnique\ProjectTechniqueRepositoryInterface;
use App\Repository\Squad\SquadRepositoryInterface;
use App\Repository\Status\StatusRepositoryInterface;
use App\Repository\Technique\TechniqueRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\UserProjects\UserProjectsRepositoryInterface;
use App\Service\Imgur\ImgurService;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService implements ProjectServiceInterface
{
    private User $user;
    private Project $newProject;
    public function __construct(
        private readonly UserProjectsRepositoryInterface $userProjectsRE,
        private readonly BrandRepositoryInterface $brandRE,
        private readonly StatusRepositoryInterface $statusRE,
        private readonly ProjectRepositoryInterface $projectRE,
        private readonly UserRepositoryInterface $userRE,
        private readonly LevelRepositoryInterface $levelRE,
        private readonly TechniqueRepositoryInterface $techniqueRE,
        private readonly ProjectTechniqueRepositoryInterface $projectTechniqueRepository,
        private readonly ElementRepositoryInterface $elementRepository,
        private readonly SquadRepositoryInterface $squadRepository,
        private readonly ElementUpdateRepositoryInterface $elementUpdateRepository,
        private readonly ImageRepositoryInterface $imageRepository,
        private readonly EntityManagerInterface $em,
        private readonly ImgurService $imgurSE
    ) {}

    public function createProject(array $projectData, User $user): void
    {
        $this->user = $user;
        $project = new Project();

        $status = $this->statusRE->find(1);
        $level = $this->levelRE->find($projectData['level']);
        $brand = $this->brandRE->find($projectData['brand']);

        $project->setStatus($status)
            ->setLevel($level)
            ->setBrand($brand)
            ->setName($projectData['name'])
            ->setCreationDate(new \DateTime())
            ->setLastUpdate(new \DateTime());

        if(isset($projectData['image']) && $projectData['image'] !== "") {
            $imageURL = $this->imgurSE->uploadImage($projectData['image']);
            $project->setImage($imageURL);
        }

        if(isset($projectData['description']) && $projectData['description'] !== "") $project->setDescription($projectData['description']);

        $this->em->persist($project);
        $this->newProject = $project;

        $userProject = $this->addUser();
        $userProject->setPriority($projectData['priority']);

        if(isset($projectData['users']) && count($projectData['users']) > 0) {
            $users = $projectData['users'];
            foreach($users as $userId) {
                $this->addUser($userId);
            }
        }

        if(isset($projectData['techniques']) && count($projectData['techniques']) > 0) {
            foreach($projectData['techniques'] as $techniqueId) {
                $technique = $this->techniqueRE->find($techniqueId);
                $projectTechnique = new ProjectTechnique();
                $projectTechnique->setProject($project);
                $projectTechnique->setTechnique($technique);
                $this->em->persist($projectTechnique);
            }
        }
    }

    private function addUser(int $userId = null): UserProjects
    {
        $userProject = new UserProjects();
        $userProject->setProject($this->newProject)
            ->setIncorporationDate(new \DateTime());

        if(!$userId) {
            $userProject->setUser($this->user);
        } else {
            $user = $this->userRE->find($userId);
            $userProject->setUser($user);
            $userProject->setPriority(false);
        }

        $this->em->persist($userProject);
        return $userProject;
    }

    /**
     * @throws CustomMessageException
     */
    public function addExtraUser(array $projectData): void
    {
        $userId = $projectData['userId'];
        $projectId = $projectData['projectId'];

        $user = $this->userRE->find($userId);
        $project = $this->projectRE->find($projectId);

        $isInProject = $this->checkIfUserAlreadyInProject($user, $project);
        if($isInProject) throw new CustomMessageException('User is already in that project');

        $userProject = new UserProjects();
        $userProject->setProject($project)
            ->setUser($user)
            ->setIncorporationDate(new \DateTime());

        $this->em->persist($userProject);
    }

    private function checkIfUserAlreadyInProject(User $user, Project $project): bool
    {
        $userProject = $this->userProjectsRE->findOneBy(['user' => $user, 'project' => $project]);
        if($userProject instanceof UserProjects) return true;
        return false;
    }

    public function editProject(array $newProjectData): void
    {
        $projectId = $newProjectData['projectId'];
        $project = $this->projectRE->find($projectId);
        if(!$project instanceof Project) throw new EntityNotFoundException('Project');

        if(isset($newProjectData['status'])) {
            $status = $this->statusRE->find($newProjectData['status']);
            if(!$status instanceof Status) throw new EntityNotFoundException('Status');
            $project->setStatus($status);
        }

        if(isset($newProjectData['level'])) {
            $level = $this->levelRE->find($newProjectData['level']);
            if(!$level instanceof Level) throw new EntityNotFoundException('Level');
            $project->setLevel($level);
        }

        if(isset($newProjectData['brand'])) {
            $brand = $this->brandRE->find($newProjectData['brand']);
            if(!$brand instanceof Brand) throw new EntityNotFoundException('Brand');
            $project->setBrand($brand);
        }

        if(isset($newProjectData['name'])) {
            $project->setName($newProjectData['name']);
        }

        if(isset($newProjectData['description'])) {
            $project->setDescription($newProjectData['description']);
        }

        if(isset($newProjectData['isPriority'])) {
            $project->setPriority($newProjectData['isPriority']);
        }

        if(isset($newProjectData['image'])) {
            $imageURL = $this->imgurSE->uploadImage($newProjectData['image']);
            $project->setImage($imageURL);
        }

        $this->em->persist($project);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function togglePriority(string $userProjectId, User $user): void
    {
        $userProject = $this->userProjectsRE->find($userProjectId);
        if(!$userProject instanceof UserProjects || $userProject->getUser() !== $user) throw new CustomMessageException('You cannot edit that project');

        $projectPriority = $userProject->isPriority();
        $userProject->setPriority(!$projectPriority);
        $this->em->persist($userProject);
    }


    public function getProjectInfoById(int $projectId): array
    {
        $projectBasicInfo = $this->projectRE->getProjectBasicInfoById($projectId);
        $projectTechniques = $this->projectTechniqueRepository->getProjectTechniquesByProjectId($projectId);
        $projectElements = $this->elementRepository->getElementsByProjectId($projectId);
        $projectSquads = $this->squadRepository->getSquadsByProjectId($projectId);
        $projectSquads = $this->getElementsBySquad($projectSquads);
        $projectGallery = $this->imageRepository->getImagesByProjectId($projectId);
        $projectUpdates = $this->getUpdatesByProjectId($projectId);

        $projectBasicInfo['techniques'] = $projectTechniques;
        $projectBasicInfo['elements'] = $projectElements;
        $projectBasicInfo['squads'] = $projectSquads;
        $projectBasicInfo['gallery'] = $projectGallery;
        $projectBasicInfo['updates'] = $projectUpdates;

        return $projectBasicInfo;
    }

    private function getElementsBySquad(array $squads): array
    {
        foreach($squads as &$squad) {
            $squadId = $squad['id'];
            $elements = $this->elementRepository->getElementsBySquad($squadId);
            $squad['elements'] = $elements;
        }

        return $squads;
    }

    private function getUpdatesByProjectId(int $projectId): array
    {
        $updates = $this->elementUpdateRepository->getUpdatesByProjectId($projectId);
        $updates = $this->addImagesAndElementsToUpdates($updates);

        return $updates;
    }

    private function addImagesAndElementsToUpdates(array $updates): array
    {
        foreach($updates as &$update) {
            $images = $this->imageRepository->getImagesByUpdateId($update['id']);
            $elements = $this->elementUpdateRepository->getElementsAndSquadsByUpdateId($update['id']);
            $update['images'] = $images;
            $update['elements'] = $elements;
        }

        return $updates;
    }


}