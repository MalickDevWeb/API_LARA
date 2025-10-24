<?php

namespace App\Exceptions;

use App\Enums\ErrorEnum;

class InsufficientBalanceException extends CustomException
{
    public function __construct()
    {
        parent::__construct(ErrorEnum::INSUFFICIENT_BALANCE);
    }
}