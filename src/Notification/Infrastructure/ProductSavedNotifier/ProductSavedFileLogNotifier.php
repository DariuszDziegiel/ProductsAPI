<?php

declare(strict_types=1);

namespace Notification\Infrastructure\ProductSavedNotifier;

use Api\Domain\Event\ProductSavedEvent;
use Notification\Domain\ProductSavedNotifierInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['product.saved.event.notifier'])]
readonly class ProductSavedFileLogNotifier implements ProductSavedNotifierInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function notify(ProductSavedEvent $productSavedEvent): void
    {
        $product = $productSavedEvent->product;

        $this->logger->info("Saved product: ({$product->id()})");
    }
}
