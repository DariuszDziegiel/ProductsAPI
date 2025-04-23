<?php

declare(strict_types=1);

namespace Api\Domain\Repository;

use Api\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function save(Category $category): void;
}
