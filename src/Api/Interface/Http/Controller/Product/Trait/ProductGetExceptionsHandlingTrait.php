<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product\Trait;

use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

trait ProductGetExceptionsHandlingTrait
{
    private function handleHandlerFailedException(HandlerFailedException $e): JsonResponse
    {
        foreach ($e->getWrappedExceptions() as $wrappedException) {
            if ($wrappedException instanceof ProductWithGivenIdNotExistsException) {
                return new JsonResponse([
                    'message' => 'Product with given id not exists'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        return new JsonResponse([
            'message' => 'Unexpected exception: ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
