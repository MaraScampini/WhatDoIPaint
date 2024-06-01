<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ControllerService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends ControllerService
{
    #[Route('/auth/register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em): Response
    {
        $request = $this->transformJsonBody($request);
        $email = $request->get('email');
        $password = $request->get('password');
        $username = $request->get('username');

        $user = new User();
        $user->setEmail($email)->setPassword(password_hash($password, PASSWORD_DEFAULT))->setUsername($username);

        $em->persist($user);

        try {
            $em->flush();
        } catch (\Throwable $th) {
            throw $th;
        }

        return new Response('User registered successfully');
    }

    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRE, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $JWTTokenManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $plainPassword = $data['password'];

        $user = $userRE->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json([
                'status' => 401,
                'message' => 'These credentials do not match our records.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $isValid = $passwordHasher->isPasswordValid($user, $plainPassword, null);


        if (!$isValid) {
            return $this->json([
                'status' => 401,
                'message' => 'These credentials do not match our records.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $JWTToken = $JWTTokenManager->create($user);

        return $this->json([
            'status' => 200,
            'token' => $JWTToken
        ]);
    }

}
