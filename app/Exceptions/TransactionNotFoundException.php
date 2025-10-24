<?php

namespace App\Exceptions;

use App\Enums\ErrorEnum;

class TransactionNotFoundException extends CustomException
{
    public function __construct()
    {
        parent::__construct(ErrorEnum::TRANSACTION_NOT_FOUND);
    }
}