<?php

declare(strict_types=1);

namespace Api\Interface\Controller\Product;

use Api\Application\Exception\Product\ProductWithGivenIdAlreadyExistsException;
use Api\Application\UseCase\ProductAdd\ProductAddCommand;
use Api\Interface\RequestDTO\ProductAddRequestDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class ProductAddController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {}

    #[Route(
        '/products',
        methods: ['POST']
    )]
    public function __invoke(
        #[MapRequestPayload] ProductAddRequestDTO $productAddRequestDTO
    ): JsonResponse
    {
        try {
            $id = $productAddRequestDTO->id ?? Uuid::v7()->toString();

            $this->commandBus->dispatch(
                new ProductAddCommand(
                    $id,
                    $productAddRequestDTO->title,
                    $productAddRequestDTO->price
                )
            );

            return $this->buildCreatedResponse($id);
        } catch (ValidationFailedException $e) {
            return $this->buildValidationErrorResponse($e);
        } catch(HandlerFailedException $e) {
            return $this->handleHandlerFailedException($e);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error:' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function buildCreatedResponse(string $id): JsonResponse
    {
        $response = new JsonResponse([
            'message' => 'Product created successfully'
        ], Response::HTTP_CREATED);

        $response->headers->set('Location', "/api/products/{$id}");

        return $response;
    }

    private function buildValidationErrorResponse(ValidationFailedException $e): JsonResponse
    {
        $violations = iterator_to_array($e->getViolations());
        $messages = array_map(fn($v) => $v->getMessage(), $violations);

        return $this->json([
            'message' => 'Validation failed: ' . implode(', ', $messages)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function handleHandlerFailedException(HandlerFailedException $e): JsonResponse
    {
        foreach ($e->getWrappedExceptions() as $wrappedException) {
            if ($wrappedException instanceof ProductWithGivenIdAlreadyExistsException) {

                return $this->json([
                    'message' => 'Product already exists'
                ], Response::HTTP_CONFLICT);
            }
        }

        return $this->json([
            'message' => 'Unexpected exception: ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
