<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Update\UpdateServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UpdateController extends AbstractController
{
    #[Route('/update/{updateId}', methods: ['GET'])]
    public function getUpdateInformation(UpdateServiceInterface $updateService, int $updateId): Response
    {
        $updateInformation = $updateService->getUpdateInformation($updateId);

        return new JsonResponse($updateInformation);
    }
}
