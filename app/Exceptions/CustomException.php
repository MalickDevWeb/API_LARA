<?php

namespace App\Exceptions;

use App\Enums\ErrorEnum;
use Exception;

abstract class CustomException extends Exception
{
    protected ErrorEnum $error;

    public function __construct(ErrorEnum $error)
    {
        $this->error = $error;
        parent::__construct($error->message());
    }

    public function getError(): ErrorEnum
    {
        return $this->error;
    }
}