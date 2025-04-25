<?php

declare(strict_types=1);

namespace Api\Domain\Repository;

use Api\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;
    public function findById(string $id): ?Product;
    public function remove(Product $product): void;
    /** @return Product[] */
    public function findAll(int $limit = 25, int $page = 0): array;
}
