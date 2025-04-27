<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductDelete;

use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Api\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
#[AsMessageHandler('command.bus')]
class ProductDeleteCommandHandler
{
    public function __construct(
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineProductRepository')]
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(ProductDeleteCommand $productDeleteCommand): void
    {
        $product = $this->productRepository->findById($productDeleteCommand->id);
        if (!$product) {
            throw new ProductWithGivenIdNotExistsException();
        }

        $this->productRepository->remove($product);
    }

}
