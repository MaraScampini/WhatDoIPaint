<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\User\UserRepository;
use App\Service\Auth\AuthServiceInterface;
use App\Service\ControllerService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
//        $data = $request->request->all();

        $userToken = $authService->login($data);

        return new Response($userToken);
    }

}
