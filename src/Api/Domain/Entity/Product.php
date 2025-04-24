<?php

declare(strict_types=1);

namespace Api\Domain\Entity;

use Api\Domain\Entity\Traits\TimestampableTrait;
use Api\Domain\ValueObject\Money;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
#[Table]
#[HasLifecycleCallbacks]
class Product
{
    use TimestampableTrait;

    #[Id]
    #[Column(type: 'string')]
    #[Assert\Uuid()]
    private string $id;

    #[Column(type: 'string')]
    #[Assert\NotBlank]
    private string $title;

    #[Embedded(class: Money::class)]
    private Money $price;

    public function __construct(
        string $id,
        string $title,
        Money $price
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
    }

    public function id(): string
    {
        return $this->id;
    }

}
