<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductAdd;

use Api\Domain\Entity\Product;
use Api\Domain\Repository\ProductRepositoryInterface;
use Api\Domain\ValueObject\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
class ProductAddCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function __invoke(ProductAddCommand $command): void
    {
        $this->productRepository->save(
            new Product(
                $command->id,
                $command->title,
                Money::createFromPrice($command->price)
            )
        );
    }

}
