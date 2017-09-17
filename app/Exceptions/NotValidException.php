<?php

namespace Cemal\Exceptions;

use Exception;

/**
 * exception class for single not valid value.
 */
class NotValidException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
