<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductsList;

use Api\Application\UseCase\ProductsList\DTO\ProductListItemDTO;
use Api\Domain\Entity\Product;
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

        $products = $this->productRepository->findAll(
            $productsListQuery->limit,
            $productsListQuery->page
        );

        $productsItemsDTOs = array_map(
            fn(Product $product) => new ProductListItemDTO(
                $product->id(),
                $product->title(),
                $product->price()->value(),
                $product->categoriesCodes(),
                $product->createdAt()->format('Y-m-d H:i:s'),
                $product->updatedAt()?->format('Y-m-d H:i:s')
            ),
            $products
        );

        return $productsItemsDTOs;
    }
}
