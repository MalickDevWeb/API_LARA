<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case DEPOT = 'depot';
    case RETRAIT = 'retrait';

    public function label(): string
    {
        return match($this) {
            self::DEPOT => 'Dépôt',
            self::RETRAIT => 'Retrait',
        };
    }
}