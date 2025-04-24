<?php

declare(strict_types=1);

namespace Api\Interface\Exception;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsEventListener('kernel.exception')]
class ApiHttpExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof UnprocessableEntityHttpException) {
            $event->setResponse(new JsonResponse([
                'message' => 'Request payload validation failed - ' . $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
            return;
        }

        if ($e instanceof BadRequestHttpException) {
            $event->setResponse(new JsonResponse([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST));
            return;
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $allowedMethods = $e->getHeaders()['Allow'] ?? [];
            $message = sprintf(
                "Method Not Allowed. Allowed methods: %s",
                is_array($allowedMethods) ? implode(',', $allowedMethods) : $allowedMethods
            );

            $event->setResponse(new JsonResponse([
                'message' => $message
            ], Response::HTTP_METHOD_NOT_ALLOWED));
            return;
        }

    }
}
