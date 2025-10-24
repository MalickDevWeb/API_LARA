<?php

namespace App\Exceptions;

use App\Enums\ErrorEnum;

class UnauthorizedException extends CustomException
{
    public function __construct()
    {
        parent::__construct(ErrorEnum::UNAUTHORIZED);
    }
}