<?php

declare(strict_types=1);

namespace Api\Domain\Exception\Category;

use Api\Domain\Exception\DomainException;

class CategoryCodeTooLongException extends DomainException
{
    public $message = 'Category code too long. Max 10 characters allowed.';
}
