<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Project\ProjectServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ElementController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }
    #[Route('/element', methods: ['POST'])]
    public function addElementsToProject(Request $request, ProjectServiceInterface $projectService): Response
    {
        $elementData = json_decode($request->getContent(), true);

        $projectService->addElementsToProject($elementData);

        try {
            $this->em->flush();
        } catch (\Exception $exception) {
            return new Response('Elements could not be added', 500);
        }
        return new Response('Elements added successfully', 200);
    }
}
