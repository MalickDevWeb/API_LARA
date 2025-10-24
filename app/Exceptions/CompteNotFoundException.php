<?php

namespace App\Exceptions;

use App\Enums\ErrorEnum;

class CompteNotFoundException extends CustomException
{
    public function __construct()
    {
        parent::__construct(ErrorEnum::COMPTE_NOT_FOUND);
    }
}