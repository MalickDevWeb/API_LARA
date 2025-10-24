<?php

namespace App\Enums;

enum StatutEnum: string
{
    case ACTIF = 'actif';
    case BLOQUE = 'bloque';
    case FERME = 'ferme';
    case INACTIF = 'inactif';
    case EN_ATTENTE = 'en_attente';
    case ANNULEE = 'annulee';
    case VALIDEE = 'validee';

    public function label(): string
    {
        return match($this) {
            self::ACTIF => 'Actif',
            self::BLOQUE => 'Bloqué',
            self::FERME => 'Fermé',
            self::INACTIF => 'Inactif',
            self::EN_ATTENTE => 'En Attente',
            self::ANNULEE => 'Annulée',
            self::VALIDEE => 'Validée',
        };
    }
}