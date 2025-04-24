<?php

declare(strict_types=1);

namespace Api\Interface\RequestDTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Uuid;

readonly class ProductAddRequestDTO
{
    public function __construct(
        #[PositiveOrZero]
        #[NotBlank]
        public readonly string $price,
        #[NotBlank]
        public readonly string $title,
        #[Uuid]
        public readonly ?string $id = null
    ) {}
}
