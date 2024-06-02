<?php

namespace App\EventListener;

use App\Exception\CustomMessageException;
use App\Exception\EntityNotFoundException;
use App\Exception\InvalidCredentialsException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: ExceptionEvent::class)]
class ExceptionHandler
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof EntityNotFoundException ||
            $exception instanceof InvalidCredentialsException ||
            $exception instanceof CustomMessageException) {

            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getCode());

            $event->setResponse($response);
        }
    }
}