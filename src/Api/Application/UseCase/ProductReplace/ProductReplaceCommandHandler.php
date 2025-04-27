<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductReplace;

use Api\Application\Exception\Product\ProductWithGivenIdNotExistsException;
use Api\Domain\Entity\Category;
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
class ProductReplaceCommandHandler
{
    public function __construct(
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineProductRepository')]
        private readonly ProductRepositoryInterface $productRepository,
        #[Autowire(service: 'Api\Infrastructure\Persistence\Doctrine\DoctrineCategoryRepository')]
        private readonly CategoryRepositoryInterface $categoryRepository,
        #[Target('event.bus')]
        private readonly MessageBusInterface $eventBus
    ) {}

    public function __invoke(ProductReplaceCommand $productReplaceCommand): void
    {
        $product = $this->productRepository->findById($productReplaceCommand->id);

        if (!$product) {
            throw new ProductWithGivenIdNotExistsException();
        }

        $product->updateTitle($productReplaceCommand->title);
        $product->updatePrice(Money::createFromPrice($productReplaceCommand->price));
        $product->clearCategories();

        //@TODO: move categories adding to dedicated service
        foreach ($productReplaceCommand->categories as $categoryCode) {
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
