<?php

declare(strict_types=1);

namespace Api\Interface\Http\RequestDTO;

use Symfony\Component\Validator\Constraints\LessThanOrEqual;

readonly class ProductsListQueryDTO
{
    public function __construct(
        #[LessThanOrEqual(500)]
        public int $limit = 25,
        public int $page = 0,
    ) {}
}
