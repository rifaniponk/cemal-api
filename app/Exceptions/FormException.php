<?php

namespace Cemal\Exceptions;

use Exception;
use Illuminate\Validation\Validator;

/**
 * exception class for form error validation.
 */
class FormException extends Exception
{
    protected $validator;

    public function __construct(Validator $validator, $message = 'Form Error')
    {
        parent::__construct($message);
        $this->validator = $validator;
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function getMessages()
    {
        return $this->validator->getMessageBag()->toArray();
    }
}
