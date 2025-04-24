<?php

declare(strict_types=1);

namespace Notification\Infrastructure\ProductSavedNotifier;

use Api\Domain\Event\ProductSavedEvent;
use Notification\Domain\ProductSavedNotifierInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Autoconfigure(tags: ['product.saved.event.notifier'])]
readonly class ProductSavedFileLogNotifier implements ProductSavedNotifierInterface
{
    public function __construct(
        #[Autowire(service: 'monolog.logger.product.saved.event')]
        private LoggerInterface $logger
    ) {}

    public function notify(ProductSavedEvent $productSavedEvent): void
    {
        $product = $productSavedEvent->product;
        $productDateUpdate = $product->updatedAt()
            ? $product->updatedAt()->format('Y-m-d H:i:s')
            : '---'
        ;

        $this
            ->logger
            ->info('Product saved', [
                'id'      => $product->id(),
                'title'   => $product->title(),
                'price'   => $product->price()->value(),
                'date_creation' => $product->createdAt()->format('Y-m-d H:i:s'),
                'date_update'   => $productDateUpdate
            ]);
    }
}
