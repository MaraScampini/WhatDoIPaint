<?php

namespace App\Exception;

class EntityNotFoundException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message . ' not found', 404);
    }
}