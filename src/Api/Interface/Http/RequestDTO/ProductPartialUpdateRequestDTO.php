<?php

declare(strict_types=1);

namespace Api\Interface\Http\RequestDTO;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

readonly class ProductPartialUpdateRequestDTO
{
    public function __construct(
        public ?string $price = null,
        public ?string $title = null,
        public ?array $categories = null,
    ) {}
}
