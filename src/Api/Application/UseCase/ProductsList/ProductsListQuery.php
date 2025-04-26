<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductsList;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

#[AsMessage('sync')]
class ProductsListQuery
{
    public function __construct(
        public ?int $limit = 100,
        public ?int $page = 1
    ) {}
}
