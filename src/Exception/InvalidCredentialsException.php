<?php

namespace App\Exception;

class InvalidCredentialsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Invalid credentials', 401);
    }

}