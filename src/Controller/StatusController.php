<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utilities\StaticUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StatusController extends AbstractController
{
    #[Route('/status/selector')]
    public function getStatusSelector(Request $request): JsonResponse
    {
        $headers = $request->headers->get('Authorization');

        return new JsonResponse('The header is ' . $headers);

        $status = StaticUtilities::getStatusSelector();
        return new JsonResponse($status);
    }
}
