<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/me')]
    public function tokenInformation(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userData = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'role' => $user->getRoles()
        ];

        return new JsonResponse($userData);
    }
}
