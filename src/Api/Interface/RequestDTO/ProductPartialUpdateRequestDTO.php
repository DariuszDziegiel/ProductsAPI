<?php

declare(strict_types=1);

namespace Api\Interface\RequestDTO;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Uuid;

readonly class ProductPartialUpdateRequestDTO
{
    public function __construct(
        #[PositiveOrZero]
        #[NotBlank]
        public string $price,
        #[NotBlank]
        public string $title,
        #[Count(min: 1)]
        #[NotBlank]
        #[All([
            new Length(min: 1, max: 10),
        ])]
        public array $categories,
        #[Uuid]
        public ?string $id = null
    ) {}
}
