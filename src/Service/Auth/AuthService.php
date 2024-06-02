<?php

namespace App\Service\Auth;

use App\Entity\User;
use App\Exception\InvalidCredentialsException;
use App\Repository\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService implements AuthServiceInterface
{


    public function __construct(
        private readonly UserRepositoryInterface     $userRE,
        private readonly EntityManagerInterface      $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface    $JWTTokenManager
    )
    {
    }

    public function register(array $userData): void
    {
        $user = new User();
        $user->setEmail($userData['email'])
            ->setPassword(password_hash($userData['password'], PASSWORD_DEFAULT))
            ->setUsername($userData['username']);

        $this->em->persist($user);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function login(array $credentials): string
    {
        $user = $this->userRE->findOneBy(['email' => $credentials['email']]);
        if (!$user instanceof User) throw new InvalidCredentialsException();

        $isValidPassword = $this->passwordHasher->isPasswordValid($user, $credentials['password'], null);
        if (!$isValidPassword) throw new InvalidCredentialsException();

        return $this->JWTTokenManager->create($user);
    }
}