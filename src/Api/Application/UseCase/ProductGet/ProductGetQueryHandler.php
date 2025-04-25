<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductGet;

use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Api\Application\UseCase\ProductGet\DTO\ProductGetDTO;
use Api\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
class ProductGetQueryHandler
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function __invoke(ProductGetQuery $productGetQuery): ProductGetDTO
    {
        $product = $this->productRepository->findById($productGetQuery->id);

        if (!$product) {
            throw new ProductWithGivenIdNotExistsException();
        }

        return new ProductGetDTO(
            $product->id(),
            $product->title(),
            $product->price()->value(),
            $product->categoriesCodes(),
            $product->createdAt()->format('Y-m-d H:i:s'),
            $product->updatedAt()?->format('Y-m-d H:i:s')
        );
    }

}
