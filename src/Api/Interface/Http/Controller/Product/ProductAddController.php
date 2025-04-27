<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product;

use Api\Application\Exception\Product\ProductWithGivenIdAlreadyExistsException;
use Api\Application\UseCase\ProductAdd\ProductAddCommand;
use Api\Interface\Http\Controller\Product\Trait\ProductAddExceptionsHandlingTrait;
use Api\Interface\Http\Controller\Product\Trait\ValidationErrorHandlingTrait;
use Api\Interface\Http\RequestDTO\ProductAddRequestDTO;
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
    use ProductAddExceptionsHandlingTrait;
    use ValidationErrorHandlingTrait;

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
                    $productAddRequestDTO->price,
                    $productAddRequestDTO->categories
                )
            );

            return new JsonResponse(
                [
                    'message' => 'Product created successfully'
                ],
                Response::HTTP_CREATED,
                [
                    'Location' => "/products/{$id}"
                ]
            );
        } catch (ValidationFailedException $e) {
            return $this->handleValidationErrorResponse($e);
        } catch(HandlerFailedException $e) {
            return $this->handleHandlerFailedException($e);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error - we will fix it as soon as possible - please try again later'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
