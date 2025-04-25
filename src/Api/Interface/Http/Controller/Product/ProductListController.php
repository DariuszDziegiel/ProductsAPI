<?php

declare(strict_types=1);

namespace Api\Interface\Http\Controller\Product;

use Api\Application\UseCase\ProductsList\ProductsListQuery;
use Api\Interface\Http\RequestDTO\ProductsListQueryDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;


class ProductListController extends AbstractController
{
    public function __construct(
        #[Target('query.bus')]
        private readonly MessageBusInterface $queryBus,
    ) {}

    #[Route(
        '/products',
        methods: ['GET', 'HEAD']
    )]
    public function __invoke(
        #[MapQueryString] ProductsListQueryDTO $productsListQueryDTO
    ): JsonResponse
    {
        try {
            $envelope = $this->queryBus->dispatch(
                new ProductsListQuery(
                    $productsListQueryDTO->limit,
                    $productsListQueryDTO->page
                )
            );
            $handled = $envelope->last(HandledStamp::class);
            $products = $handled->getResult();

            return $this->json([
                'data' => $products
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->json([
                'message' => 'Unexpected error:' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
