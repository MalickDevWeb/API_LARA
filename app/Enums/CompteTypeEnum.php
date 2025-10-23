<?php

namespace App\Enums;

enum CompteTypeEnum: string
{
    case EPARGNE = 'epargne';
    case COURANT = 'courant';

    public function label(): string
    {
        return match($this) {
            self::EPARGNE => 'Compte Épargne',
            self::COURANT => 'Compte Courant',
        };
    }
}