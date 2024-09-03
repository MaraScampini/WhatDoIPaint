<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\Project\ProjectServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
