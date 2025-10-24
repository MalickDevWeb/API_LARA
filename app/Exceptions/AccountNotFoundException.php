<?php

namespace App\Exceptions;

use App\Enums\ErrorEnum;

class AccountNotFoundException extends CustomException
{
    public function __construct()
    {
        parent::__construct(ErrorEnum::ACCOUNT_NOT_FOUND);
    }
}