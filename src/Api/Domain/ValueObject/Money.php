<?php

declare(strict_types=1);

namespace Api\Domain\ValueObject;

use Api\Domain\ValueObject\Exception\NegativePriceArgumentException;
use Api\Domain\ValueObject\Exception\NotNumericPriceFormatException;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

//@TODO: add currency support and subtract method in next release
#[Embeddable]
class Money
{
    #[Column(
        name: 'price',
        type: 'decimal',
        precision: 10,
        scale: 2
    )]
    #[PositiveOrZero]
    #[NotBlank]
    private string $price;

    public function __construct(string $price)
    {
        if (!is_numeric($price)) {
            throw new NotNumericPriceFormatException("Price must be a numeric value.");
        }

        if (bccomp($price, '0.00', 2) < 0) {
            throw new NegativePriceArgumentException('Price cannot be negative.');
        }

        $this->price = $price;
    }

    public static function createFromPrice(string $price): self
    {
        return new self($price);
    }

    public function __toString(): string
    {
        return $this->price;
    }

    public function value(): string
    {
        return $this->price;
    }

    public function equals(Money $otherMoney): bool
    {
        return $this->price === $otherMoney->price;
    }

    public function add(Money $otherMoney): self
    {
        $sum = bcadd(
            $this->price,
            $otherMoney->price,
            2
        );

        return new self($sum);
    }
}
