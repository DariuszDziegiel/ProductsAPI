<?php

declare(strict_types=1);

namespace Api\Interface\Controller\Product;

use Api\Application\UseCase\ProductAdd\ProductAddCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class ProductAddController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
    }

    #[Route(
        '/products',
        methods: ['POST']
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->getPayload()->all();
        $id =  $payload['id'] ?? Uuid::v4()->toString();

        try {
            $this->commandBus->dispatch(
                new ProductAddCommand(
                    $id,
                    $payload['title'],
                    $payload['price']
                )
            );

            $response = new JsonResponse([
                'message' => 'Product created successfully'
            ], Response::HTTP_CREATED);
            $response->headers->set('location', "/products/{$id}");

            return $response;
        } catch (ValidationFailedException $validationFailedException) {

            return $this->json([
                'message' => 'Validation failed'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {

            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
