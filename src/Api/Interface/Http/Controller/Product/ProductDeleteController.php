<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product;

use Api\Application\UseCase\ProductDelete\ProductDeleteCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

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
