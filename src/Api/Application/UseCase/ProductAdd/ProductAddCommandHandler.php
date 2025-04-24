<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductAdd;

use Api\Domain\Entity\Product;
use Api\Domain\Event\ProductSavedEvent;
use Api\Domain\Repository\ProductRepositoryInterface;
use Api\Domain\ValueObject\Money;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler('command.bus')]
class ProductAddCommandHandler
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        #[Target('event.bus')]
        private readonly MessageBusInterface $eventBus
    ) {}

    public function __invoke(ProductAddCommand $command): void
    {
        $product = new Product(
            $command->id,
            $command->title,
            Money::createFromPrice($command->price)
        );

        $this->productRepository->save($product);

        $this->eventBus->dispatch(
            ProductSavedEvent::createFromProduct($product)
        );
    }

}
