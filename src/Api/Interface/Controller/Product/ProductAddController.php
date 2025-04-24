<?php

declare(strict_types=1);

namespace Api\Interface\Controller\Product;

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
        private readonly MessageBusInterface $commandBus
    ) {}

    #[Route(
        '/products',
        methods: ['POST']
    )]
    public function __invoke(
        #[MapRequestPayload] ProductAddRequestDTO $productAddRequestDTO
    ): JsonResponse {
        try {
            $id =  $productAddRequestDTO->id ?? Uuid::v4()->toString();

            $this->commandBus->dispatch(
                new ProductAddCommand(
                    $id,
                    $productAddRequestDTO->title,
                    $productAddRequestDTO->price
                )
            );
            $response = new JsonResponse([
                'message' => 'Product created successfully'
            ], Response::HTTP_CREATED);
            $response->headers->set('location', "/api/products/{$id}");

            return $response;
        } catch (ValidationFailedException) {

            return $this->json([
                'message' => 'Validation failed'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (HandlerFailedException $e) {

            return $this->json([
                'message' => $e->getPrevious()?->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {

            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
