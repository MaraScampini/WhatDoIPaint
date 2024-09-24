<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserProjects\UserProjectsRepositoryInterface;
use App\Service\Project\ProjectServiceInterface;
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
    public function getUserProjects(UserProjectsRepositoryInterface $userProjectsRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $projects = $userProjectsRepository->getProjectsByUser($user);

        return new JsonResponse($projects);
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

    #[Route('/project/toggle/{id}', methods: ['PUT'])]
    public function toggleProjectPriority(ProjectServiceInterface $projectService, $id): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $projectService->togglePriority($id, $user);
            $this->em->flush();
        } catch (\Exception $e) {
            return new Response('The project could not be edited', 500);
        }
        return new Response('Priority toggled correctly');
    }
}
