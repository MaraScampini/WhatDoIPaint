<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utilities\StaticUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class StatusController extends AbstractController
{
    #[Route('/status/selector')]
    public function getStatusSelector(): JsonResponse
    {
        $status = StaticUtilities::getStatusSelector();
        return new JsonResponse($status);
    }
}
