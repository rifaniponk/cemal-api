<?php
namespace Cemal\Exceptions;

use Exception;

/**
 * exception class for authorization violation.
 */
class AccessDeniedException extends Exception
{
    public function __construct($message = 'Akses ditolak')
    {
        parent::__construct($message);
    }
}