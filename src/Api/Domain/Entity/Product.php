<?php

declare(strict_types=1);

namespace Api\Domain\Entity;

use Api\Domain\Entity\Traits\TimestampableTrait;
use Api\Domain\ValueObject\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
#[Table]
#[HasLifecycleCallbacks]
class Product
{
    use TimestampableTrait;

    #[Id]
    #[Column(type: 'string', length: 36)]
    #[Assert\Uuid()]
    private string $id;

    #[Column(type: 'string')]
    #[Assert\NotBlank]
    private string $title;

    #[Embedded(class: Money::class)]
    private Money $price;

    #[ManyToMany(
        targetEntity: Category::class,
        inversedBy: 'products',
        cascade: ['persist']
    )]
    #[JoinTable(name: 'product_category')]
    #[Assert\Count(min: 1)]
    private Collection $categories;

    public function __construct(
        string $id,
        string $title,
        Money $price
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->categories = new ArrayCollection();
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function clearCategories(): self
    {
        $this->categories->clear();
        return $this;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function updateTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }


    public function price(): Money
    {
        return $this->price;
    }

    public function updatePrice(Money $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function categoriesCodes(): array
    {
        return array_map(
            fn($category) => $category->code(),
            $this->categories->toArray()
        );
    }

}
