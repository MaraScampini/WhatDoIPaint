<?php

namespace App\Service\Project;

use App\Entity\Project;
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

    /**
     * @throws EntityNotFoundException
     */
    public function     createProject(array $projectData, User $user): void
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

    /**
     * @throws EntityNotFoundException
     */
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
     * @throws EntityNotFoundException
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

    public function checkIfUserAlreadyInProject(User $user, Project $project): bool
    {
        $userProject = $this->userProjectsRE->findOneBy(['user' => $user, 'project' => $project]);
        if($userProject instanceof UserProjects) return true;
        return false;
    }

}