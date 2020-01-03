<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    protected $code    = 404;
    protected $message = 'resource_not_found';
}
