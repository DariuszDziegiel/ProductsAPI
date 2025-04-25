<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductsList;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

#[AsMessage('sync')]
class ProductsListQuery
{
    public function __construct(
        #[LessThanOrEqual(500)]
        public int $limit,
        public int $page
    ) {}
}
