<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utilities\StaticUtilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class TechniqueController extends AbstractController
{
    #[Route('/technique/selector')]
    public function getTechniqueSelector(): JsonResponse
    {
       $techniques = StaticUtilities::getTechniquesSelector();
       return new JsonResponse($techniques);
    }
}
