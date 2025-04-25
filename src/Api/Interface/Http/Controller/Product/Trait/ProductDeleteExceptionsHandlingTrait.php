<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product\Trait;

use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

trait ProductDeleteExceptionsHandlingTrait
{
    private function handleHandlerFailedException1(HandlerFailedException $e): Response
    {
        foreach ($e->getWrappedExceptions() as $wrappedException) {
            if ($wrappedException instanceof ProductWithGivenIdNotExistsException) {

                return new Response(
                    null,
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        return new JsonResponse([
            'message' => 'Unexpected exception: ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
