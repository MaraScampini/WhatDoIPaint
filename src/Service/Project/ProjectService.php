<?php

namespace App\Service\Project;

use App\Entity\Brand;
use App\Entity\Level;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\UserProjects;
use App\Exception\CustomMessageException;
use App\Exception\EntityNotFoundException;
use App\Repository\Brand\BrandRepositoryInterface;
use App\Repository\Level\LevelRepositoryInterface;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\Status\StatusRepositoryInterface;
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
        private readonly EntityManagerInterface $em,
        private readonly ImgurService $imgurSE
    ) {}

    public function createProject(array $projectData, User $user): void
    {
        $this->user = $user;
        $project = new Project();

        $status = $this->statusRE->find($projectData['statusId']);
        $level = $this->levelRE->find($projectData['levelId']);
        $brand = $this->brandRE->find($projectData['brandId']);

        $project->setStatus($status)
            ->setLevel($level)
            ->setBrand($brand)
            ->setName($projectData['name'])
            ->setCreationDate(new \DateTime())
            ->setLastUpdate(new \DateTime())
            ->setPriority(false);

        if(isset($projectData['image'])) {
            $imageURL = $this->imgurSE->uploadImage($projectData['image']);
            $project->setImage($imageURL);
        }

        if(isset($projectData['description'])) $project->setDescription($projectData['description']);

        $this->em->persist($project);
        $this->newProject = $project;

        $this->addUser();

        if(isset($projectData['users'])) {
            $users = $projectData['users'];
            foreach($users as $userId) {
                $this->addUser($userId);
            }
        }
    }

    private function addUser(int $userId = null): void
    {
        $userProject = new UserProjects();
        $userProject->setProject($this->newProject)
            ->setIncorporationDate(new \DateTime());

        if(!$userId) {
            $userProject->setUser($this->user);
        } else {
            $user = $this->userRE->find($userId);
            $userProject->setUser($user);
        }

        $this->em->persist($userProject);
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
}