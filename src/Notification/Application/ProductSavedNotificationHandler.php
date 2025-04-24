<?php

declare(strict_types=1);

namespace Notification\Application;

use Api\Domain\Event\ProductSavedEvent;
use Notification\Domain\ProductSavedNotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('event.bus')]
readonly class ProductSavedNotificationHandler
{
    public function __construct(
        /** @var ProductSavedNotifierInterface[] */
        #[AutowireIterator('product.saved.event.notifier')]
        private iterable $productSavedNotifiers
    ) {}

    public function __invoke(ProductSavedEvent $productSavedEvent): void
    {
        foreach ($this->productSavedNotifiers as $productSavedNotifier) {
            $productSavedNotifier->notify($productSavedEvent);
        }
    }
}
