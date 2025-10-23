<?php

namespace App\Enums;

enum StatutEnum: string
{
    case ACTIF = 'actif';
    case BLOQUE = 'bloque';
    case INACTIF = 'inactif';

    public function label(): string
    {
        return match($this) {
            self::ACTIF => 'Actif',
            self::BLOQUE => 'Bloqué',
            self::INACTIF => 'Inactif',
        };
    }
}