<?php

namespace App\Exception;

class CustomMessageException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, 400);
    }
}