<?php

namespace App\Service\Project;

use App\Entity\Project;
use App\Entity\User;
use App\Entity\UserProjects;
use App\Exception\CustomMessageException;
use App\Exception\EntityNotFoundException;
use App\Service\ImgurService;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService implements ProjectServiceInterface
{
    private User $user;
    private Project $newProject;
    public function __construct(
        private readonly ProjectRepositoryService $projectRepositorySE,
        private readonly EntityManagerInterface $em,
        private readonly ImgurService $imgurSE
    ) {}

    /**
     * @throws EntityNotFoundException
     */
    public function createProject(array $projectData, User $user): void
    {
        $this->user = $user;
        $project = new Project();

        $status = $this->projectRepositorySE->getStatus($projectData['statusId']);
        $level = $this->projectRepositorySE->getLevel($projectData['levelId']);
        $brand = $this->projectRepositorySE->getBrand($projectData['brandId']);



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
            $user = $this->projectRepositorySE->getUser($userId);
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

        $user = $this->projectRepositorySE->getUser($userId);
        $project = $this->projectRepositorySE->getProject($projectId);

        $isInProject = $this->projectRepositorySE->checkIfUserAlreadyInProject($user, $project);
        if($isInProject) throw new CustomMessageException('User is already in that project');

        $userProject = new UserProjects();
        $userProject->setProject($project)
            ->setUser($user)
            ->setIncorporationDate(new \DateTime());

        $this->em->persist($userProject);
    }


}