<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductsList;

use Api\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
class ProductsListQueryHandler
{
    public function __construct(
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineProductRepository')]
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function __invoke(ProductsListQuery $productsListQuery)
    {
        // TODO: Implement __invoke() method.
        return [
            'data' => [
                ['title' => 'test1'],
                ['title' => 'test2'],
            ]
        ];
    }

}
