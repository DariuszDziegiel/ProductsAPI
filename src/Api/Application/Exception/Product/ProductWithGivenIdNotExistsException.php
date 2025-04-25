<?php

declare(strict_types=1);

namespace Api\Application\Exception\Product;

use Api\Application\Exception\ApplicationException;

class ProductWithGivenIdNotExistsException extends ApplicationException
{
    public $message = 'Product with given id not exists';
}
