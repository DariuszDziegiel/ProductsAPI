<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductsList\DTO;

readonly class ProductListItemDTO
{
    public function __construct(
        public string $id,
        public string $title,
        public string $price,
        public array $categories,
        public string $createdAt,
        public ?string $updatedAt = null
    ) {}
}
