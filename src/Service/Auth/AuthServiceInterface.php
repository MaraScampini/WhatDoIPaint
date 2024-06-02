<?php

namespace App\Service\Auth;

use Symfony\Component\HttpFoundation\Response;

interface AuthServiceInterface
{
    public function register(array $userData): void;
    public function login(array $credentials): string;
}