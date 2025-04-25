<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductPartialUpdate;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Uuid;

#[AsMessage('sync')]
readonly class ProductPartialUpdateCommand
{
    public function __construct(
        #[Uuid]
        public string $id,
        public ?string $title = null,
        public ?string $price = null,
        public ?array $categories = null,
    ) {}

}
