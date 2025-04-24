<?php

declare(strict_types=1);

namespace Notification\Domain;

use Api\Domain\Event\ProductSavedEvent;

interface ProductSavedNotifierInterface
{
    public function notify(ProductSavedEvent $productSavedEvent);
}
