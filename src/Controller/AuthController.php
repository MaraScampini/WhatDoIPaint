<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\Streak\StreakRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Service\Auth\AuthServiceInterface;
use App\Service\ControllerService;
use App\Service\Streak\StreakServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends ControllerService
{
    #[Route('/auth/register', methods: ['POST'])]
    public function register(Request $request, AuthServiceInterface $authService, EntityManagerInterface $em): Response
    {
        $request = json_decode($request->getContent(), true);
        $authService->register($request);

        try {
            $em->flush();
        } catch (\Throwable $th) {
            return new Response('The user could not be registered', 500);
        }

        return new Response('User registered successfully');
    }

    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request, AuthServiceInterface $authService): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
        $userToken = $authService->login($data);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }

        return new Response($userToken);
    }

    #[Route('/dev', methods: ['GET'])]
    public function devEndpoint(UserRepositoryInterface $userRepository,ProjectRepositoryInterface $projectRepository, StreakRepositoryInterface $streakRepository, StreakServiceInterface $streakService): Response
    {
        $user = $userRepository->find(1);
//        $response = $streakRepository->getStreakByUser($user);
        $response = $streakService->createOrUpdateStreak($user);

        return new JsonResponse($response);
    }

}
