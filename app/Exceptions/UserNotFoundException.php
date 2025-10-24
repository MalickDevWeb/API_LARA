<?php

namespace App\Exceptions;

use App\Enums\ErrorEnum;

class UserNotFoundException extends CustomException
{
    public function __construct()
    {
        parent::__construct(ErrorEnum::USER_NOT_FOUND);
    }
}