<?php

namespace App\Enums;

enum DeviseEnum: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case XOF = 'XOF';

    public function label(): string
    {
        return match($this) {
            self::USD => 'Dollar Américain',
            self::EUR => 'Euro',
            self::XOF => 'Franc CFA',
        };
    }
}