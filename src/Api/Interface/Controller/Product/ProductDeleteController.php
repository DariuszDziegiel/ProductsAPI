<?php

declare(strict_types=1);

namespace Api\Interface\Controller\Product;

use Api\Application\Exception\Product\ProductWithGivenIdAlreadyExistsException;
use Api\Application\UseCase\ProductAdd\ProductAddCommand;
use Api\Application\UseCase\ProductDelete\ProductDeleteCommand;
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

class ProductDeleteController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {}

    #[Route(
        '/products/{id}',
        methods: ['DELETE']
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new ProductDeleteCommand($id)
            );

            return $this->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error:' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
