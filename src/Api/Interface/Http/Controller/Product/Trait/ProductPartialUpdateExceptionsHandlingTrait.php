<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product\Trait;

use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Api\Domain\Exception\Category\CategoryCodeTooLongException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

trait ProductPartialUpdateExceptionsHandlingTrait
{

    private function handleHandlerFailedException(HandlerFailedException $e): JsonResponse
    {
        foreach ($e->getWrappedExceptions() as $wrappedException) {
            if ($wrappedException instanceof ProductWithGivenIdNotExistsException) {

                return new JsonResponse([
                    'message' => $e->getMessage()
                ], Response::HTTP_NOT_FOUND);
            }

            if ($wrappedException instanceof CategoryCodeTooLongException) {
                return new JsonResponse([
                    'message' => $e->getMessage()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return new JsonResponse([
            'message' => 'Unexpected exception: ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
