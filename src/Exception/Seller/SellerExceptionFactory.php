<?php

namespace App\Exception\Seller;

use Throwable;

class SellerExceptionFactory
{
    public static function emailAlreadyExists(
        string $message = '',
        ?Throwable $previous = null)
    {
        return new EmailAlreadyExistsException(
            ($message ?: 'Email already exists'),
            409,
            $previous
        );
    }
}