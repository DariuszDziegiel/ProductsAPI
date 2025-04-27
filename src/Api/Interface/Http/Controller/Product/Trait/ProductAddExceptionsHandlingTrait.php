<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product\Trait;

use Api\Application\Exception\ApplicationException;
use Api\Application\Exception\Product\ProductWithGivenIdAlreadyExistsException;
use Api\Domain\Exception\DomainException;
use Api\Domain\ValueObject\Exception\NotNumericPriceFormatException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

trait ProductAddExceptionsHandlingTrait
{
    private function handleHandlerFailedException(HandlerFailedException $e): JsonResponse
    {
        foreach ($e->getWrappedExceptions() as $wrappedException) {
            if ($wrappedException instanceof ProductWithGivenIdAlreadyExistsException) {

                return new JsonResponse([
                    'message' => 'Product already exists'
                ], Response::HTTP_CONFLICT);
            }

            if ($wrappedException instanceof NotNumericPriceFormatException) {
                return new JsonResponse([
                    'message' => 'Price must be a numeric value string'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }



        return new JsonResponse([
            'message' => 'Unexpected exception: ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
