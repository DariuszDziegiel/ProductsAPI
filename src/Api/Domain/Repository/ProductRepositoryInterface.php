<?php

declare(strict_types=1);

namespace Api\Domain\Repository;

use Api\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;
}
