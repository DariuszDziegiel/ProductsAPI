<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductAdd;

use Api\Application\Exception\Product\ProductWithGivenIdAlreadyExistsException;
use Api\Domain\Entity\Category;
use Api\Domain\Entity\Product;
use Api\Domain\Event\ProductSavedEvent;
use Api\Domain\Repository\CategoryRepositoryInterface;
use Api\Domain\Repository\ProductRepositoryInterface;
use Api\Domain\ValueObject\Money;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler('command.bus')]
class ProductAddCommandHandler
{
    public function __construct(
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineProductRepository')]
        private readonly ProductRepositoryInterface $productRepository,
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineCategoryRepository')]
        private readonly CategoryRepositoryInterface $categoryRepository,
        #[Target('event.bus')]
        private readonly MessageBusInterface $eventBus
    ) {}

    public function __invoke(ProductAddCommand $command): void
    {
        if ($this->productRepository->findById($command->id)) {
            throw new ProductWithGivenIdAlreadyExistsException('Product with given id already exists');
        }

        $product = new Product(
            $command->id,
            $command->title,
            Money::createFromPrice($command->price)
        );

        foreach ($command->categories as $categoryCode) {
            $category = $this->categoryRepository->findOneByCode($categoryCode);
            if (!$category) {
                $category = new Category(
                    Uuid::v7()->toString(),
                    $categoryCode
                );
            }
            $product->addCategory($category);
        }

        $this->productRepository->save($product);

        $this->eventBus->dispatch(
            ProductSavedEvent::createFromProduct($product)
        );
    }

}
