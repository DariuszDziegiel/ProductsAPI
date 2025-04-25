<?php

declare(strict_types=1);

namespace Api\Domain\Entity;

use Api\Domain\Entity\Traits\TimestampableTrait;
use Api\Domain\Exception\Category\CategoryCodeTooLongException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

#[Entity]
#[Table]
#[HasLifecycleCallbacks]
class Category
{
    use TimestampableTrait;

    #[Id]
    #[Column(type: 'string', length: 36)]
    #[Uuid]
    private string $id;

    #[Column(type: 'string', length: 10, unique: true)]
    #[Length(min: 1, max: 10)]
    #[NotBlank]
    private string $code;

    #[ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    private Collection $products;

    public function __construct(
        string $id,
        string $code,
    ) {
        if (mb_strlen($code) > 10) {
            throw new CategoryCodeTooLongException();
        }

        $this->id = $id;
        $this->code = $code;
        $this->products = new ArrayCollection();
    }

    public function code(): string
    {
        return $this->code;
    }

}
