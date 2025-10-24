<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case DEPOT = 'depot';
    case RETRAIT = 'retrait';
    case VIREMENT = 'virement';
    case FRAIS = 'frais';

    public function label(): string
    {
        return match($this) {
            self::DEPOT => 'Dépôt',
            self::RETRAIT => 'Retrait',
            self::VIREMENT => 'Virement',
            self::FRAIS => 'Frais',
        };
    }
}