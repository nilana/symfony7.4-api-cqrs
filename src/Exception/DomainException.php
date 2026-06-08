<?php

namespace App\Exception;

use Exception;

class DomainException extends Exception
{
    protected $code = 400;
}
