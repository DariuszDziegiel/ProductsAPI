<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductAdd;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Uuid;

#[AsMessage('sync')]
readonly class ProductAddCommand
{
    public function __construct(
        #[Uuid]
        public string $id,
        #[NotBlank]
        public string $title,
        #[NotBlank]
        #[PositiveOrZero]
        public string $price
    ) {}

}
