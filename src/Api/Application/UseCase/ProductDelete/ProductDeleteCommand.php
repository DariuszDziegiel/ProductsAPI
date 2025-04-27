<?php

declare(strict_types=1);

namespace Api\Application\UseCase\ProductDelete;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

#[AsMessage('sync')]
class ProductDeleteCommand
{
    public function __construct(
        #[Uuid]
        #[NotBlank]
        public string $id,
    ) {}
}
