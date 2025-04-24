<?php

declare(strict_types=1);

namespace Api\Domain\Event;

use Api\Domain\Entity\Product;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class ProductSavedEvent
{
    public function __construct(
        public Product $product
    ) {}

    public static function createFromProduct(Product $product): self
    {
        return new self($product);
    }
}
