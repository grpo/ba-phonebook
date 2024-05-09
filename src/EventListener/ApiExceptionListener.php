<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof ApiException) {
            $event->setResponse(
                new JsonResponse(json_decode($event->getThrowable()->getMessage(), true), Response::HTTP_BAD_REQUEST)
            );
        }
    }
}
