<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductPartialUpdate;

use Api\Application\Exception\Product\ProductWithGivenIdAlreadyExistsException;
use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Api\Application\UseCase\ProductAdd\ProductAddCommand;
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
class ProductPartialUpdateCommandHandler
{
    public function __construct(
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineProductRepository')]
        private readonly ProductRepositoryInterface $productRepository,
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineCategoryRepository')]
        private readonly CategoryRepositoryInterface $categoryRepository,
        #[Target('event.bus')]
        private readonly MessageBusInterface $eventBus
    ) {}

    public function __invoke(ProductPartialUpdateCommand $productPartialUpdateCommand): void
    {
        $product = $this->productRepository->findById($productPartialUpdateCommand->id);

        if (!$product) {
            throw new ProductWithGivenIdNotExistsException();
        }

        if ($productPartialUpdateCommand->title) {
            $product->updateTitle($productPartialUpdateCommand->title);
        }

        if ($productPartialUpdateCommand->price) {
            $product->updatePrice(
                Money::createFromPrice($productPartialUpdateCommand->price)
            );
        }

        if ($productPartialUpdateCommand->categories) {
            $product->clearCategories();
            foreach ($productPartialUpdateCommand->categories as $categoryCode) {
                $category = $this->categoryRepository->findOneByCode($categoryCode);
                if (!$category) {
                    $category = new Category(
                        Uuid::v7()->toString(),
                        $categoryCode
                    );
                }
                $product->addCategory($category);
            }
        }

        $this->productRepository->save($product);

        $this->eventBus->dispatch(
            ProductSavedEvent::createFromProduct($product)
        );
    }

}
