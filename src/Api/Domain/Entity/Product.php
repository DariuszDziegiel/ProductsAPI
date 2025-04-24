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
    #[Assert\Count(min: 8)]
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
            //$category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

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

    public function price(): Money
    {
        return $this->price;
    }
}
