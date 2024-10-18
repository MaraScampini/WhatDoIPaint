<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Update;
use App\Entity\User;
use App\Service\Update\UpdateServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UpdateController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/update/{updateId}', methods: ['GET'])]
    public function getUpdateInformation(UpdateServiceInterface $updateService, int $updateId): Response
    {
        $updateInformation = $updateService->getUpdateInformation($updateId);

        return new JsonResponse($updateInformation);
    }

    #[Route('/update/short/{projectId}', methods: ['POST'])]
    public function createShortUpdate(UpdateServiceInterface $updateService, int $projectId): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $updateService->createShortUpdate($projectId, $user);
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return new Response('The update could not be saved', 500);
        }

        return new Response('Update created successfully', 200);
    }

    #[Route('/update', methods: ['POST'])]
    public function createUpdate(UpdateServiceInterface $updateService, Request $request): Response
    {
        $updateData = json_decode($request->getContent(), true);

        $updateService->createUpdate($updateData);

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return new Response('Update could not be saved', 500);
        }
        return new Response('Update created successfully', 200);
    }
}
