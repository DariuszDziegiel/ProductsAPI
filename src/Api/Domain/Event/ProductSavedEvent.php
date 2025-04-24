<?php

declare(strict_types=1);

namespace Api\Domain\Event;

use Api\Domain\Entity\Product;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('sync')]
readonly class ProductSavedEvent
{
    public function __construct(
        public Product $product
    ) {}
}
