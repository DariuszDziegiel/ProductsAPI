<?php

declare(strict_types=1);

namespace Api\Domain\Entity;

use Api\Domain\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping\Column;
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

    #[Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\PositiveOrZero()]
    #[Assert\NotBlank]
    private string $price;

    public function __construct(
        string $id,
        string $title,
        string $price
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
