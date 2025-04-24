<?php

declare(strict_types=1);

namespace Notification\Infrastructure\ProductSavedNotifier;

use Api\Domain\Event\ProductSavedEvent;
use Notification\Domain\ProductSavedNotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;


//#[Autoconfigure(tags: ['product.saved.event.notifier'])]
readonly class ProductSavedSMSNotifier implements ProductSavedNotifierInterface
{

    //@TODO: inject SMS provider gateway to constructor
    public function __construct()
    {
    }

    public function notify(ProductSavedEvent $productSavedEvent): void
    {
        //@TODO: And send SMS by your favorite provider gateway:)
    }
}
