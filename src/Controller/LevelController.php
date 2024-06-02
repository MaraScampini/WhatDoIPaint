<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utilities\StaticUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class LevelController extends AbstractController
{
    #[Route('/level/selector')]
    public function getLevelSelector(): JsonResponse
    {
        $levels = StaticUtilities::getLevelSelector();
        return new JsonResponse($levels);
    }
}
