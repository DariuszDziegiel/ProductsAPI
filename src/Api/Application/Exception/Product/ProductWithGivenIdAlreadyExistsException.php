<?php

declare(strict_types=1);

namespace Api\Application\Exception\Product;

use Api\Application\Exception\ApplicationException;

class ProductWithGivenIdAlreadyExistsException extends ApplicationException
{
    public $message = 'Product with given id already exists';
}
