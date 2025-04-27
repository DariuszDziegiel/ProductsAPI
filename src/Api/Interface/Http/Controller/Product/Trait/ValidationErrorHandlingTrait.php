<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product\Trait;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

trait ValidationErrorHandlingTrait
{
    private function handleValidationErrorResponse(ValidationFailedException $e): JsonResponse
    {
        $violations = iterator_to_array($e->getViolations());
        $messages = array_map(fn($v) => $v->getMessage(), $violations);

        return new JsonResponse([
            'message' => 'Validation failed: ' . implode(', ', $messages)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
