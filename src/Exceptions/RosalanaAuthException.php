<?php

namespace Rosalana\Accounts\Exceptions;

use Exception;

class RosalanaAuthException extends Exception
{
    protected $errors;
    protected $status;

    public function __construct($errors, $status = 401)
    {
        $this->errors = $errors;
        $this->status = $status;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getStatus()
    {
        return $this->status;
    }
}