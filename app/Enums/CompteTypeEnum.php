<?php

namespace App\Enums;

enum CompteTypeEnum: string
{
    case EPARGNE = 'epargne';
    case CHEQUE = 'cheque';
    case COURANT = 'courant';

    public function label(): string
    {
        return match($this) {
            self::EPARGNE => 'Compte Épargne',
            self::CHEQUE => 'Compte Chèque',
            self::COURANT => 'Compte Courant',
        };
    }
}