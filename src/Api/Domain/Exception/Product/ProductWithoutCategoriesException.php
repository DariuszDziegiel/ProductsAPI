<?php

declare(strict_types=1);

namespace Api\Domain\Exception\Product;

class ProductWithoutCategoriesException extends \DomainException
{
    public $message = 'Product must have at least one category.';
}
