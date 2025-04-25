<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductGet;

use Api\Application\Exception\Product\ProductWithGivenIdAlreadyExistsException;
use Api\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
class ProductGetQueryHandler
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function __invoke(ProductGetQuery $productGetQuery)
    {
        if (!$this->productRepository->findById($productGetQuery->id)) {
            throw new ProductWithGivenIdAlreadyExistsException();
        }

        // TODO: Implement __invoke() method.
        return [
            'title' => 'test1'
        ];
    }

}
