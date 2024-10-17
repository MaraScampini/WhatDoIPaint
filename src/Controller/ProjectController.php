<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\Image\ImageRepositoryInterface;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\UserProjects\UserProjectsRepositoryInterface;
use App\Service\Project\ProjectServiceInterface;
use App\Service\Update\UpdateServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ProjectController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/project', methods: ['GET'])]
    public function getUserProjects(UserProjectsRepositoryInterface $userProjectsRepository, Request $request): Response
    {
        $params = $request->query->all();
        /** @var User $user */
        $user = $this->getUser();

        $projects = $userProjectsRepository->getProjectsByUser($user, $params);

        return new JsonResponse($projects);
    }

    #[Route('/project/updates/{projectId}', methods: ['GET'])]
    public function getUpdatesByProjectId(UpdateServiceInterface $updateService, Request $request, int $projectId): Response
    {
        $page = $request->query->getInt('page');

        $updates = $updateService->getUpdatesByProjectId($projectId, $page);
        return new JsonResponse($updates);
    }

    #[Route('/project/gallery/{projectId}', methods: ['GET'])]
    public function getGalleryByProjectId(ProjectServiceInterface $projectService, Request $request, int $projectId): Response
    {
        $page = $request->query->getInt('page');
        $limit = $request->query->getInt('limit');

        $gallery = $projectService->getProjectGallery($projectId, $page, $limit);
        return new JsonResponse($gallery);
    }

    #[Route('/project/elements/selector/{projectId}', methods: ['GET'])]
    public function getElementsByProjectId(ProjectServiceInterface $projectService, int $projectId): Response
    {
        $elementsAndSquads = $projectService->getElementsByProjectId($projectId);
        return new JsonResponse($elementsAndSquads);
    }

    #[Route('/project/squads/selector/{projectId}', methods: ['GET'])]
    public function getSquadsByProjectId(ProjectServiceInterface $projectService, int $projectId): Response
    {
        $elementsAndSquads = $projectService->getSquadsByProjectId($projectId);
        return new JsonResponse($elementsAndSquads);
    }

    #[Route('/project/wdip', methods: ['GET'])]
    public function getRandomProject(ProjectRepositoryInterface $projectRepository, Request $request): Response
    {
        $queryParams = $request->query->all();
        /** @var User $user */
        $user = $this->getUser();

        $project = $projectRepository->getRandomProject($queryParams, $user);

        return new JsonResponse($project);
    }

    #[Route('/project/{id}', methods: ['GET'])]
    public function getProjectById(ProjectServiceInterface $projectService, int $id): Response
    {
        $projectInfo = $projectService->getProjectInfoById($id);

        return new JsonResponse($projectInfo);
    }

    #[Route('/project', methods: ['POST'])]
    public function createProject(Request $request, ProjectServiceInterface $projectSE): Response
    {
        $projectData = json_decode($request->getContent(), true);
        /** @var User $user */
        $user = $this->getUser();

        $projectSE->createProject($projectData, $user);

        try {
            $this->em->flush();
        } catch (\Exception $exception) {
            return new Response('Project could not be saved', 500);
        }
        return new Response('Project created successfully');
    }

    #[Route('/project/add/user', methods: ['POST'])]
    public function addUserToProject(Request $request, ProjectServiceInterface $projectSE): Response
    {
        $request = json_decode($request->getContent(), true);

        $projectSE->addExtraUser($request);

        try {
            $this->em->flush();
        } catch (\Exception $exception) {
            return new Response('User could not be added to the project', 500);
        }

        return new Response('User added successfully to the project');
    }

    #[Route('/project', methods: ['PUT'])]
    public function editProject(Request $request, ProjectServiceInterface $projectSE): Response
    {
        $newProjectData = json_decode($request->getContent(), true);

        $projectSE->editProject($newProjectData);

        try {
            $this->em->flush();
        } catch (\Exception $exception) {
            return new Response('Project could not be edited', 500);
        }

        return new Response('Project edited successfully');
    }

    #[Route('/project/toggle/{projectUserId}', methods: ['PUT'])]
    public function toggleProjectPriority(ProjectServiceInterface $projectService, $projectUserId): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $projectService->togglePriority($projectUserId, $user);
            $this->em->flush();
        } catch (\Exception $e) {
            return new Response('The project could not be edited', 500);
        }
        return new Response('Priority toggled correctly');
    }


}
