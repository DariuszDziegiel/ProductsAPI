<?php

declare(strict_types=1);

namespace Api\Interface\Http\RequestDTO;

use Symfony\Component\Validator\Constraints\LessThanOrEqual;

readonly class ProductsListQueryDTO
{
    public function __construct(
        public ?int $limit = 100,
        public ?int $page = 1,
    ) {}
}
